# Image
FROM cupy/cupy:v8.0.0

# Set up work directory
WORKDIR /var/www/python

# Configure host
ENV HOST 0.0.0.0

CMD ["sh", "-c","pip install -r requirements.txt && python3 api.py"]
