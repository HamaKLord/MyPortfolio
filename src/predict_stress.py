from flask import Flask, request, jsonify
from flask_cors import CORS
from mood_analysis import analyze_mood

app = Flask(__name__)
CORS(app)

@app.route('/predict', methods=['POST'])
def predict():
    data = request.json
    mood_text = data.get('mood', '')

    if not mood_text:
        return jsonify({'error': 'Please enter a mood description'}), 400

    result = analyze_mood(mood_text)
    return jsonify({'message': result})

if __name__ == '__main__':
    app.run(debug=True, port=5000)
