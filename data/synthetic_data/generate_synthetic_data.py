import pandas as pd
import random

# Generate synthetic mood data
def generate_synthetic_data(num_samples=100):
    moods = ['I feel great!', 'I am stressed out', 'Feeling anxious', 'Calm and peaceful']
    labels = [0, 1, 1, 0]

    data = {
        'text': [random.choice(moods) for _ in range(num_samples)],
        'label': [random.choice(labels) for _ in range(num_samples)]
    }

    df = pd.DataFrame(data)
    df.to_csv('./synthetic_data/synthetic_mood_data.csv', index=False)
    print("Synthetic data generated and saved.")

generate_synthetic_data()
