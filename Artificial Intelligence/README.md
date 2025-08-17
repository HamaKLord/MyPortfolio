# AI in Mental Health: Personalized Stress Management

## Project Overview
This project, **AI in Mental Health: Personalized Stress Management**, uses **Python** and **React** to help users analyze their stress levels and receive personalized recommendations. The backend is built with **Flask**, and an AI model predicts stress levels based on user inputs. Each file in the project has a specific purpose, contributing to a complete end-to-end solution.

---

## Files and Their Purpose

### **1. feedback_loop.py**
- Collects user feedback (e.g., actual stress levels) to improve the AI model over time.
- Uses `pandas` to save feedback in a CSV file.

### **2. generate_synthetic_data.py**
- Creates synthetic data for training and testing the AI model.
- Uses `pandas` and `random` to generate and save data.

### **3. logger.py**
- Keeps logs of important events, errors, or processes.
- Uses Python's `logging` module to create separate logs for training and API activity.

### **4. mood_analysis.py**
- Loads the trained AI model to analyze user mood and predict stress levels.
- Uses `joblib` for loading the model and `sklearn` for text processing.

### **5. predict_stress.py**
- Connects the frontend with the backend for mood predictions.
- Uses `Flask` to handle requests and send responses.

### **6. recommendations.py**
- Provides personalized suggestions based on predicted stress levels.
- Pure Python logic, no external libraries needed.

### **7. server.py**
- Manages the backend server.
- Routes handled:
  - `/predict`: Predicts stress.
  - `/feedback`: Saves user feedback.
  - `/trends`: Shows past stress trends.
- Uses `Flask` and `pandas`.

### **8. train_model.py**
- Trains the AI model using labeled data.
- Uses `sklearn` for model training and `pandas` for data handling.
- Saves the model with `joblib`.

---

## How It Works
1. **Frontend (React)**: Users input their mood.  
2. **Backend (Flask)**: Processes inputs and sends stress predictions.  
3. **AI Model**: Analyzes data and predicts stress levels.  
4. **Feedback Loop**: Improves the system over time with user feedback.  

**Libraries Used**: `pandas`, `joblib`, `sklearn`, `flask` – for data handling, model training, saving progress, and server management.

---

## Folder Structure and Files

### **Processed Folder**
- `mood_data_cleaned.csv`: Cleaned, normalized dataset used for machine learning.

### **Raw Folder**
- `emotion_sentimen_dataset.csv`: Raw labeled emotion/sentiment data.
- `test.csv`: Data for testing model performance.
- `wesad_dataset.csv`: Physiological data from WESAD dataset for stress detection.

### **Synthetic Data Folder**
- `generate_synthetic_data.py`: Script to generate synthetic datasets.
- `generate_synthetic_data.csv`: Generated synthetic data.
- `synthetic_mood_data.csv`: Synthetic mood data to supplement training datasets.

### **Notebooks Folder**
- `data_preprocessing.ipynb`: Jupyter notebook for cleaning and preprocessing raw datasets.
- `data_preprocessing.py`: Script for automated data preprocessing.
- `data_preprocessing.txt`: Documentation or logs of preprocessing steps.
- `model_training.ipynb`: Notebook for training and evaluating the AI model.

---

## Identifying Labeled Datasets
- **Check columns**: Look for columns like `Category`, `Emotion`, or `Stress_Level`.  
- **Unique values**: Categories or numerical codes indicate labels.  
- **Unlabeled datasets**: Lack a ground truth column.  

### **Labeling Methods**
- **Manual Labeling**: Human-tagged data.  
- **Automatic Labeling**: Using rules or models.  
- **Example in Project**:
  - Text keywords like "stressed" → `"High Stress"`.
  - Heart rate above a threshold → `"High Stress"` automatically.

---

This structure ensures that the project is **organized, understandable, and ready for further development**.

