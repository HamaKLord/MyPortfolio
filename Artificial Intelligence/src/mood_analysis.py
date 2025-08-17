import joblib
import os
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.ensemble import RandomForestClassifier

class MoodAnalyzer:
    def __init__(self):
        self.model = None
        self.vectorizer = None
        self.crisis_keywords = [
            'kill myself', 'suicide', 'die', 'end my life', 'death',
            'self harm', 'hurt myself', 'don\'t want to live'
        ]
        self.high_stress_keywords = [
            'stress', 'anxiety', 'worried', 'depressed', 'sad',
            'angry', 'frustrated', 'overwhelmed', 'exhausted', 'tired',
            'hate', 'lonely', 'alone', 'scared', 'fear'
        ]

    def analyze_mood(self, text):
        text_lower = text.lower()
        
        # Check for crisis signals first
        if any(keyword in text_lower for keyword in self.crisis_keywords):
            return {
                'stress_level': "High Stress",
                'confidence': 0.95,
                'advice': ("I'm very concerned about what you're expressing. "
                         "Please know that you're not alone and your life has value. "
                         "Contact emergency services or crisis helpline immediately:\n"
                         "or call this phone number +964 771 812 6767 I am everytime here for you!\n"
                         "Please be patient God is always there for you\n"
                         "These services are free, and Mr.Sasan Can Be Very HElpful for these situation like this confidential, and available 24/7.")
            }
        
        # Check for high stress keywords
        elif any(keyword in text_lower for keyword in self.high_stress_keywords):
            return {
                'stress_level': "High Stress",
                'confidence': 0.85,
                'advice': self.generate_advice(text, "High Stress", 0.85)
            }
        
        # Default to moderate stress if no clear indicators
        else:
            return {
                'stress_level': "High Stress" if len(text) > 20 else "Low Stress",
                'confidence': 0.65,
                'advice': self.generate_advice(text, "Moderate", 0.65)
            }

    def generate_advice(self, text, stress_level, confidence):
        text_lower = text.lower()
        
        if stress_level == "High Stress":
            if any(word in text_lower for word in ['work', 'job', 'deadline']):
                return ("It seems you're under significant work pressure. Consider:\n"
                       "1. Breaking tasks into smaller, manageable pieces\n"
                       "2. Taking regular breaks using the 5-minute rule\n"
                       "3. Communicating with supervisors about workload\n"
                       "4. Practicing deep breathing exercises")
            
            elif any(word in text_lower for word in ['sleep', 'tired', 'exhausted']):
                return ("Your fatigue levels are concerning. Try:\n"
                       "1. Establishing a regular sleep schedule\n"
                       "2. Creating a relaxing bedtime routine\n"
                       "3. Limiting screen time before bed\n"
                       "4. Consider talking to a healthcare provider about your sleep")
            
            elif any(word in text_lower for word in ['lonely', 'alone']):
                return ("Feeling lonely is a serious concern. Consider:\n"
                       "1. Reaching out to friends or family\n"
                       "2. Joining community groups or online communities\n"
                       "3. Speaking with a counselor or therapist\n"
                       "4. Engaging in social activities or volunteering")
            
            else:
                return ("I notice you're experiencing significant stress. Here are some suggestions:\n"
                       "1. Talk to someone you trust about your feelings\n"
                       "2. Consider professional counseling or therapy\n"
                       "3. Practice self-care activities\n"
                       "4. Try meditation or mindfulness exercises")
        else:
            return ("While your stress levels seem manageable, it's good to practice prevention:\n"
                   "1. Regular exercise\n"
                   "2. Meditation or mindfulness\n"
                   "3. Maintaining social connections\n"
                   "4. Regular sleep schedule")