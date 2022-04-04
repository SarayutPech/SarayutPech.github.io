from sklearn.preprocessing import StandardScaler
from sklearn.linear_model import LinearRegression
import pandas as pd

#csv_path = data_set['csv link'][0]
csv_path = 'https://www.cryptodatadownload.com/cdd/Binance_DOGEUSDT_d.csv'
# ^^ ตัวนี้ต้อง GET มาจาก php



csv = pd.read_csv(csv_path, parse_dates=['date'],skiprows=1)
csv = csv.sort_values('date')
csv.drop(columns='unix',inplace = True)
# print(csv)

csv['tradecount'].fillna( csv['tradecount'].mean() ,inplace = True)
X = csv.iloc[:, 2:]
X.drop( columns='close' ,inplace = True)
Y = csv[[ 'close' ]]

standard_scaler = StandardScaler()
X = pd.DataFrame(standard_scaler.fit_transform(X.values), index = X.index, columns=X.columns )
corr_df = X.corr()

X = X[['open', 'high', 'low']]
test_size = int(len(X) * 0.2)
X_train = X.iloc[ test_size: , :]
X_test = X.iloc[ :test_size , :]

Y_train = Y.iloc[ test_size: , :]
Y_test = Y.iloc[ :test_size , :]

lr = LinearRegression()
lr.fit(X_train, Y_train)
round_1_y_pred = lr.predict(X_test)

ans = csv.iloc[:test_size,:]
ans['close_pred'] = round_1_y_pred
print(ans)


