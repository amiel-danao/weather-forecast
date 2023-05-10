import pandas as pd
from datetime import datetime, timedelta
from sklearn.linear_model import Ridge
from sklearn.metrics import mean_absolute_error, mean_squared_error

df = pd.read_csv("Port Area.csv", index_col="DATE")

# weather.reset_index(inplace=True)
# last_date = f"{weather['MONTH'].iloc[-1]}/{weather['DAY'].iloc[-1]}/{weather['YEAR'].iloc[-1]}"
# last_date = datetime.strptime(last_date, '%m/%d/%Y')
last_date = df.index[-1]
today = datetime.today().date()
five_days_from_today = today + timedelta(days=5)
last_date = pd.Timestamp(last_date)
date_range = pd.date_range(last_date + timedelta(days=1), five_days_from_today, freq='D')

date_range_str = date_range.strftime('%m/%d/%Y')
# date_range = date_range.astype(str)

new_dates = pd.DataFrame(index=date_range_str)
# new_dates.reset_index(inplace=True)
new_dates = new_dates.rename(columns={'index': 'DATE'})
# new_dates = new_dates[['DATE']]
# new_dates = pd.DataFrame({'DATE': date_range})

# new_dates['DATE'] = new_dates['DATE'].dt.date

# Concatenate the new DataFrame with the original DataFrame
df = pd.concat([df, new_dates])

# Reorder the columns
# weather = weather[weather.columns[::-1]]  # Reverse the column order

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

predictions = backtest(weather, rr, predictors)


print(mean_absolute_error(predictions["actual"], predictions["prediction"]))

predictions.sort_values("diff", ascending=False)

def pct_diff(old, new):
    return (new - old) / old

def compute_rolling(weather, horizon, col):
    label = f"rolling_{horizon}_{col}"
    weather[label] = weather[col].rolling(horizon).mean()
    weather[f"{label}_pct"] = pct_diff(weather[label], weather[col])
    return weather
    
rolling_horizons = [3, 14]
for horizon in rolling_horizons:
    for col in ["tmax", "tmin", "rainfall"]:
        weather = compute_rolling(weather, horizon, col)
def expand_mean(df):
    return df.expanding(1).mean()

for col in ["tmax", "tmin", "rainfall"]:
    weather[f"month_avg_{col}"] = weather[col].groupby(weather.index.month, group_keys=False).apply(expand_mean)
    weather[f"day_avg_{col}"] = weather[col].groupby(weather.index.day_of_year, group_keys=False).apply(expand_mean)

# remove the first 14 rows (because it has the missing values)
weather = weather.iloc[14:,:]
#find missing values and fill them in with 0, (some columns will still contains NAN when dividing 0 )
weather = weather.fillna(0)
predictors = weather.columns[~weather.columns.isin(["target",])]
predictions = backtest(weather, rr, predictors)
print(mean_absolute_error(predictions["actual"], predictions["prediction"]))

print(mean_squared_error(predictions["actual"], predictions["prediction"]))

print(predictions.sort_values("diff", ascending=False))

print(predictions[-100:])


weather.to_csv('prediction.csv', header=True)