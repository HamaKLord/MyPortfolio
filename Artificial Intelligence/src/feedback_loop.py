import pandas as pd  # Import pandas for data handling and CSV file operations

def collect_feedback(user_input, predicted_mood, actual_mood):
    # Create a dictionary to store the feedback details
    feedback = {
        'text': user_input,  # User's input text
        'predicted': predicted_mood,  # Predicted mood by the system
        'actual': actual_mood  # Actual mood reported by the user
    }
    
    # Save feedback to a CSV file in append mode
    df = pd.DataFrame([feedback])
    df.to_csv('./data/feedback.csv', mode='a', header=False, index=False)
    print("Feedback collected successfully.")  # Print confirmation
