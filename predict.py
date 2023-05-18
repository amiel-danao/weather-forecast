import pandas as pd
import configparser
from datetime import datetime, timedelta
from sklearn.linear_model import Ridge
from sklearn.metrics import mean_absolute_error, mean_squared_error
import mysql.connector
import os





def backtest(weather, model, predictors, start=3650, step=90):
    
    all_predictions = []
    
    for i in range(start, weather.shape[0], step):
        train = weather.iloc[:i,:]
        test = weather.iloc[i:(i+step),:]
        
        model.fit(train[predictors], train["target"])
        
        preds = model.predict(test[predictors])
        preds = pd.Series(preds, index=test.index)
        combined = pd.concat([test["target"], preds], axis=1)
        combined.columns = ["actual", "prediction"]
        combined["diff"] = (combined["prediction"] - combined["actual"]).abs()
        
        all_predictions.append(combined)
    return pd.concat(all_predictions)




# print(mean_absolute_error(predictions["actual"], predictions["prediction"]))

# predictions.sort_values("diff", ascending=False)

def pct_diff(old, new):
    return (new - old) / old


def compute_rolling(weather, horizon, col):
    label = f"rolling_{horizon}_{col}"
    weather[label] = weather[col].rolling(horizon).mean()
    weather[f"{label}_pct"] = pct_diff(weather[label], weather[col])
    return weather

def expand_mean(df):
    return df.expanding(1).mean()

def run_average(weather):
    for col in ["tmax", "tmin", "rainfall", "wind_direction", "wind_speed"]:
        weather[f"month_avg_{col}"] = weather[col].groupby(weather.index.month, group_keys=False).apply(expand_mean)
        weather[f"day_avg_{col}"] = weather[col].groupby(weather.index.day_of_year, group_keys=False).apply(expand_mean)

def run_rolling(weather):
    rolling_horizons = [3, 14]
    for horizon in rolling_horizons:
        for col in ["tmax", "tmin", "rainfall", "wind_direction", "wind_speed"]:
            weather = compute_rolling(weather, horizon, col)

    print('after rolling')

    run_average(weather)



def get_last_update(config):
    last_update = None
    ini_file_path = 'config.ini'

    # Check if the INI file exists
    if not os.path.exists(ini_file_path):
        # Add a section and an option to the INI file
        config.add_section('Settings')
        # config.set('Settings', 'last_update', 'default_date_value')

        # Save the ConfigParser object to the INI file
        with open(ini_file_path, 'w') as config_file:
            config.write(config_file)
    else:
        config.read(ini_file_path)

        try:
            # Access the values from the existing INI file
            last_update = config.get('Settings', 'last_update')
            last_update = datetime.strptime(last_update, '%Y-%m-%d')
        except configparser.NoOptionError:
            pass

    return last_update

def update_last(config):
    today = datetime.today().date().strftime('%Y-%m-%d')
    # config.set('Settings', 'last_update', today)
    config['Settings'] = {'last_update': today}
    with open('config.ini', 'w') as config_file:
        config.write(config_file)


def save_to_database(config):
    
    last_update = get_last_update(config)

    # Establish connection to the MySQL server
    cnx = mysql.connector.connect(
        user='root',
        password='',
        host='localhost',
        database='laravel'
    )

    # Create a cursor object
    cursor = cnx.cursor()

    # Load data from CSV using Pandas
    data_frame = pd.read_csv('prediction.csv')

    # Iterate over rows of the DataFrame
    for index, row in data_frame.iterrows():
        date_string = row[0]
        date_object = datetime.strptime(date_string, '%Y-%m-%d')

        if last_update is not None and date_object < last_update:
            continue
        # Extract values from the row
        rainfall = round(row['day_avg_rainfall'], 2)
        temperature_min = round(row['day_avg_tmin'], 2)
        temperature_max = round(row['day_avg_tmax'], 2)        
        temperature_mean = round((temperature_min + temperature_max) / 2, 2)
        wind_speed = round(row['day_avg_wind_speed'], 2)
        wind_direction = round(row['day_avg_wind_direction'], 2)

        select_query = "SELECT * FROM weather WHERE year = %s AND month = %s AND day = %s"
        cursor.execute(select_query, (date_object.year, date_object.month, date_object.day))
        existing_rows = cursor.fetchall()

        if len(existing_rows) > 0:
            print(f"Skipping row with date {date_string} as it already exists in the database.")
            continue

        # SQL query to insert a new row
        insert_query = "INSERT INTO weather (year, month, day, rainfall, temperature_min, temperature_max, temperature_mean, wind_speed, wind_direction) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)"

        # Values for the new row
        row_values = (date_object.year, date_object.month, date_object.day, rainfall, temperature_min, temperature_max, temperature_mean, wind_speed, wind_direction)

        # Execute the query
        cursor.execute(insert_query, row_values)

    # Commit the changes to the database
    cnx.commit()

    # Close the cursor and the connection
    cursor.close()
    cnx.close()

    update_last(config)



def main():
    config = configparser.ConfigParser()

    df = pd.read_csv("Port Area.csv", index_col="DATE")

    last_date = df.index[-1]
    today = datetime.today().date()
    fourtheen_days_from_today = today + timedelta(days=14)
    last_date = pd.Timestamp(last_date)
    date_range = pd.date_range(last_date + timedelta(days=1), fourtheen_days_from_today, freq='D')

    date_range_str = date_range.strftime('%m/%d/%Y')

    new_dates = pd.DataFrame(index=date_range_str)
    new_dates = new_dates.rename(columns={'index': 'DATE'})

    # Concatenate the new DataFrame with the original DataFrame
    df = pd.concat([df, new_dates])

    print(df)

    df.to_csv('temp_csv.csv', header=True)

    # null_pct = weather.apply(pd.isnull).sum()/weather.shape[0]

    # valid_columns = weather.columns[null_pct < .05]

    # weather = weather[valid_columns].copy()
    weather = pd.read_csv("temp_csv.csv", index_col=0)

    weather.columns = weather.columns.str.lower()

    weather = weather.ffill()

    weather.apply(pd.isnull).sum()

    weather.apply(lambda x: (x == 9999).sum())

    print(weather.index)
    weather.index = pd.to_datetime(weather.index)
    weather.index.year.value_counts().sort_index()

    weather["target"] = weather.shift(-1)["tmax"]

    weather = weather.ffill()

    rr = Ridge(alpha=.1)
    # weather = weather.drop('date', axis=1)
    predictors = weather.columns[~weather.columns.isin(["target",])]

    predictions = backtest(weather, rr, predictors)

    run_rolling(weather)


    # remove the first 14 rows (because it has the missing values)
    # weather = weather.iloc[14:,:]
    #find missing values and fill them in with 0, (some columns will still contains NAN when dividing 0 )
    weather = weather.fillna(0)
    predictors = weather.columns[~weather.columns.isin(["target",])]

    # print(predictions[100:])

    predictions = backtest(weather, rr, predictors)

    # print(mean_absolute_error(predictions["actual"], predictions["prediction"]))

    # print(mean_squared_error(predictions["actual"], predictions["prediction"]))

    # print(predictions.sort_values("diff", ascending=False))

    # print(predictions[100:])


    weather.to_csv('prediction.csv', header=True)

    save_to_database(config)

if __name__ == "__main__":
    main()