import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
import joblib

# 1. Load dữ liệu
data = pd.read_csv("E:\\DOWNLOAD\\final_test.csv")

# 2. Tách feature và label
X = data[["weight", "age", "height"]]
y = data['size']

# 3. Chia dữ liệu train/test
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# 4. Tạo model
model = RandomForestClassifier(n_estimators=100, random_state=42)
model.fit(X_train, y_train)

# 5. Đánh giá model
print("Accuracy:", model.score(X_test, y_test))

# 6. Lưu model
joblib.dump(model, "size_model.pkl")
