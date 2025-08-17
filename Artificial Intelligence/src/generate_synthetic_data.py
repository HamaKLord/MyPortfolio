import pandas as pd  # For managing and saving data
import random  # For generating random mood text and labels

def generate_synthetic_data(num_samples=100):
    # Define possible mood text and corresponding labels
    moods = ['I feel great', 'I am stressed', 'Feeling anxious', 'Calm and peaceful']
    labels = [0, 1, 1, 0]  # 0: Low stress, 1: High stress

    # Create random samples of mood text and labels
    data = {
        'text': [random.choice(moods) for _ in range(num_samples)],
        'label': [random.choice(labels) for _ in range(num_samples)]
    }

    # Save the synthetic data to a CSV file
    df = pd.DataFrame(data)
    df.to_csv('./data/synthetic_data/synthetic_mood_data.csv', index=False)
    print("Synthetic data generated successfully.")  # Print confirmation

# Generate 100 samples of synthetic data
generate_synthetic_data()
