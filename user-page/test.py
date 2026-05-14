from flask import Flask

app = Flask(__name__)

@app.route('/')
def home():
    return "SERVER WORKING"

@app.route('/health')
def health():
    return {"status": "ok"}

if __name__ == '__main__':
    print("STARTING TEST SERVER...")

    app.run(
        host='0.0.0.0',
        port=5000,
        debug=False,
        use_reloader=False
    )