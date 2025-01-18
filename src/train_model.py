from sklearn.ensemble import RandomForestClassifier
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, classification_report
import joblib
import pandas as pd

# Load preprocessed data
data = pd.read_csv('./data/processed/mood_data_cleaned.csv')

# Drop rows with missing text or labels
data.dropna(subset=['text', 'label'], inplace=True)

# Extract features and labels
X = data['text']
y = data['label']

# Fallback: Remove NaN labels from 'y'
X = X[~y.isna()]
y = y.dropna()

# Replace any remaining NaN in 'text' with empty strings
X.fillna('', inplace=True)

# Vectorize the text data
vectorizer = CountVectorizer()
X_vectorized = vectorizer.fit_transform(X)

# Split the data
X_train, X_test, y_train, y_test = train_test_split(X_vectorized, y, test_size=0.2, random_state=42)

# Train a RandomForest model
model = RandomForestClassifier(n_estimators=200, max_depth=15, random_state=42)
model.fit(X_train, y_train)

# Evaluate the model
predictions = model.predict(X_test)
print("Model Accuracy:", accuracy_score(y_test, predictions))
print(classification_report(y_test, predictions))

# Save the trained model and vectorizer
joblib.dump(model, './models/stress_model.pkl')
joblib.dump(vectorizer, './models/vectorizer.joblib')

print("Model training complete. Model and vectorizer saved.")
