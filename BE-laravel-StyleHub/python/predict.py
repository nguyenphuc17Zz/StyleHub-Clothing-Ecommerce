import sys
import joblib
import pandas as pd

# Load model đã train
model = joblib.load("D:\\model predict size\\size_model.pkl")
# Nhận input từ command line (Laravel truyền vào)
# sys.argv[1:] = [weight, age, height]
weight, age, height = map(float, sys.argv[1:])

X = pd.DataFrame([[weight, age, height]], columns=["weight", "age", "height"])

# Dự đoán
pred = model.predict(X)

# Trả kết quả cho Laravel
print(pred[0]) 
