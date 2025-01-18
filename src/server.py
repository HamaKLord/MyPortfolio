from flask import Flask, request, jsonify
from flask_cors import CORS
import pandas as pd
import datetime
import os
from mood_analysis import MoodAnalyzer

app = Flask(__name__)
CORS(app)

# Initialize the mood analyzer
mood_analyzer = MoodAnalyzer()

# Use relative path for data directory
FEEDBACK_FILE = 'data/feedback.csv'

# Ensure data directory exists
os.makedirs('data', exist_ok=True)

# Initialize feedback.csv with sample data if it doesn't exist
def initialize_feedback_file():
    if not os.path.exists(FEEDBACK_FILE) or os.path.getsize(FEEDBACK_FILE) == 0:
        # Create sample data for the last 7 days
        dates = pd.date_range(end=datetime.datetime.now(), periods=7, freq='D')
        sample_data = []
        for date in dates:
            sample_data.append({
                'timestamp': date.isoformat(),
                'text': 'Initial data point',
                'predicted': 'Low Stress',
                'actual': '',
                'stress_level': 30 + (date.day % 3) * 10,
                'confidence': 0.8
            })
        df = pd.DataFrame(sample_data)
        # Ensure the directory exists
        os.makedirs(os.path.dirname(FEEDBACK_FILE), exist_ok=True)
        # Save the file
        df.to_csv(FEEDBACK_FILE, index=False)
        print(f"Created initial data in {FEEDBACK_FILE}")

initialize_feedback_file()

@app.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.json
        mood_text = data.get('mood', '')

        if not mood_text:
            return jsonify({'error': 'Mood text is required'}), 400

        # Get analysis from the mood analyzer
        analysis = mood_analyzer.analyze_mood(mood_text)
        
        # Log the prediction
        log_prediction(mood_text, analysis)
        
        return jsonify({
            'message': analysis['stress_level'],
            'confidence': analysis['confidence'],
            'advice': analysis['advice']
        })

    except Exception as e:
        print(f"Prediction error: {str(e)}")
        return jsonify({'error': f"Failed to process request: {str(e)}"}), 500

@app.route('/feedback', methods=['POST'])
def submit_feedback():
    try:
        data = request.json
        print(f"Received feedback data: {data}")
        
        feedback_data = pd.DataFrame([{
            'timestamp': datetime.datetime.now().isoformat(),
            'text': data.get('mood', ''),
            'predicted': data.get('predictedMood', ''),
            'actual': data.get('actualMood', ''),
            'stress_level': 80 if data.get('predictedMood') == "High Stress" else 30,
            'confidence': data.get('confidence', 0.0)
        }])

        # Append to existing CSV or create new one
        feedback_data.to_csv(FEEDBACK_FILE, mode='a', header=not os.path.exists(FEEDBACK_FILE), index=False)
        print(f"Feedback saved successfully to {FEEDBACK_FILE}")

        return jsonify({'message': 'Feedback submitted successfully'})

    except Exception as e:
        print(f"Feedback error: {str(e)}")
        return jsonify({'error': str(e)}), 500

@app.route('/trends', methods=['GET'])
def get_trends():
    try:
        if not os.path.exists(FEEDBACK_FILE):
            # Return default data if file doesn't exist
            return jsonify([{
                'date': datetime.datetime.now().strftime('%Y-%m-%d'),
                'level': 50,
                'confidence': 0.5
            }])
        
        df = pd.read_csv(FEEDBACK_FILE)
        if len(df) == 0:
            return jsonify([{
                'date': datetime.datetime.now().strftime('%Y-%m-%d'),
                'level': 50,
                'confidence': 0.5
            }])
            
        df['timestamp'] = pd.to_datetime(df['timestamp'])
        df['stress_level'] = df['predicted'].map({
            'High Stress': 80,
            'Low Stress': 30
        }).fillna(50)
        
        # Group by date and calculate mean
        trends = df.groupby(df['timestamp'].dt.date).agg({
            'stress_level': 'mean',
            'confidence': 'mean'
        }).reset_index()
        
        # Format the response
        result = [{
            'date': date.strftime('%Y-%m-%d'),
            'level': float(level),
            'confidence': float(conf)
        } for date, level, conf in zip(trends['timestamp'], trends['stress_level'], trends['confidence'])]
        
        print(f"Returning trend data: {result}")  # Debug print
        return jsonify(result)
        
    except Exception as e:
        print(f"Error in trends: {str(e)}")
        return jsonify([{
            'date': datetime.datetime.now().strftime('%Y-%m-%d'),
            'level': 50,
            'confidence': 0.5
        }])

@app.route('/stats', methods=['GET'])
def get_stats():
    try:
        if not os.path.exists(FEEDBACK_FILE):
            return jsonify({
                'total_entries': 0,
                'high_stress_percentage': 0,
                'low_stress_percentage': 0,
                'average_confidence': 0
            })
        
        df = pd.read_csv(FEEDBACK_FILE)
        total_entries = len(df)
        
        if total_entries == 0:
            return jsonify({
                'total_entries': 0,
                'high_stress_percentage': 0,
                'low_stress_percentage': 0,
                'average_confidence': 0
            })
        
        high_stress = df['predicted'].value_counts().get('High Stress', 0)
        high_stress_percentage = (high_stress / total_entries) * 100
        low_stress_percentage = 100 - high_stress_percentage
        average_confidence = df['confidence'].mean()
        
        return jsonify({
            'total_entries': total_entries,
            'high_stress_percentage': high_stress_percentage,
            'low_stress_percentage': low_stress_percentage,
            'average_confidence': average_confidence
        })
        
    except Exception as e:
        print(f"Error in stats: {str(e)}")
        return jsonify({'error': str(e)}), 500

def log_prediction(text, analysis):
    """Log each prediction to the feedback file"""
    try:
        new_data = pd.DataFrame([{
            'timestamp': datetime.datetime.now().isoformat(),
            'text': text,
            'predicted': analysis['stress_level'],
            'actual': '',
            'stress_level': 80 if analysis['stress_level'] == "High Stress" else 30,
            'confidence': analysis['confidence']
        }])
        
        new_data.to_csv(FEEDBACK_FILE, mode='a', header=not os.path.exists(FEEDBACK_FILE), index=False)
        print(f"Prediction logged successfully to {FEEDBACK_FILE}")
        
    except Exception as e:
        print(f"Error logging prediction: {str(e)}")

if __name__ == '__main__':
    app.run(debug=True, port=5000)