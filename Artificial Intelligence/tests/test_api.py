import requests

def test_api():
    response = requests.post(
        'http://localhost:5000/predict',
        json={'mood': 'I feel stressed and anxious today'}
    )
    assert response.status_code == 200
    print(response.json())
