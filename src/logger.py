import logging

# Configure logging for training
logging.basicConfig(
    filename='./logs/training_logs.log',
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)

def log_training(message):
    logging.info(message)

# Configure logging for API requests
api_logger = logging.getLogger('api_logger')
api_logger.setLevel(logging.INFO)
api_handler = logging.FileHandler('./logs/api_logs.log')
api_handler.setFormatter(logging.Formatter('%(asctime)s - %(message)s'))
api_logger.addHandler(api_handler)
