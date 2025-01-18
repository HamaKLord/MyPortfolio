import pandas as pd
import string
from nltk.corpus import stopwords
import nltk

nltk.download('stopwords')
stop_words = set(stopwords.words('english'))

# Load the raw datasets
data1 = pd.read_csv('./data/raw/emotion_sentimen_dataset.csv')
data2 = pd.read_csv('./data/raw/test.csv')

# Combine datasets
combined_data = pd.concat([data1, data2], ignore_index=True)

# Drop rows with missing text or labels
combined_data.dropna(subset=['text', 'label'], inplace=True)

# Text preprocessing function
def clean_text(text):
    text = text.lower()
    text = "".join([char for char in text if char not in string.punctuation])
    words = text.split()
    words = [word for word in words if word not in stop_words]
    return " ".join(words)

# Apply preprocessing
combined_data['text'] = combined_data['text'].apply(lambda x: clean_text(x) if isinstance(x, str) else '')

# Save processed data
combined_data.to_csv('./data/processed/mood_data_cleaned.csv', index=False)

print("Preprocessing complete. Clean data saved.")
print("Checking for NaN values in labels:")
print(combined_data['label'].isna().sum())