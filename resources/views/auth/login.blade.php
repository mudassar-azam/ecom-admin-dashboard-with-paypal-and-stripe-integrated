<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}

body {
    background: linear-gradient(135deg, #3498db, #2c3e50);
    color: #333;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.login-container {
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    width: 400px;
    padding: 2rem;
    text-align: center;
}

.login-container h1 {
    margin-bottom: 1rem;
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
}

.login-container p {
    margin-bottom: 2rem;
    font-size: 0.95rem;
    color: #7f8c8d;
}

.login-container form {
    display: flex;
    flex-direction: column;
}

.login-container input {
    margin-bottom: 1.5rem;
    padding: 0.9rem 1.2rem;
    border: 1px solid #dcdfe3;
    border-radius: 8px;
    font-size: 1rem;
    color: #333;
}

.login-container input:focus {
    border-color: #3498db;
    outline: none;
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
}

.login-container button {
    background-color: #3498db;
    color: white;
    padding: 0.9rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.login-container button:hover {
    background-color: #2c80b4;
}

.login-container a {
    display: block;
    margin-top: 1rem;
    font-size: 0.9rem;
    color: #3498db;
    text-decoration: none;
    transition: color 0.3s ease;
}

.login-container a:hover {
    color: #2c3e50;
}

.footer {
    margin-top: 2rem;
    font-size: 0.85rem;
    color: #bdc3c7;
}

.footer a {
    color: #3498db;
    text-decoration: none;
}

.footer a:hover {
    text-decoration: underline;
}

.decorative-elements {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
}

.circle {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: move 8s infinite ease-in-out;
}

.circle.one {
    width: 150px;
    height: 150px;
    top: 10%;
    left: 10%;
}

.circle.two {
    width: 250px;
    height: 250px;
    top: 30%;
    left: 70%;
}

.circle.three {
    width: 200px;
    height: 200px;
    top: 70%;
    left: 30%;
}

@keyframes move {
    0%, 100% {
        transform: translate(0, 0);
    }
    50% {
        transform: translate(10px, -10px);
    }
}
</style>

<div class="decorative-elements">
    <div class="circle one"></div>
    <div class="circle two"></div>
    <div class="circle three"></div>
</div>

<div class="login-container">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email" placeholder="email" value="{{ old('email') }}" required>
        <input type="password" name="password" placeholder="password" required>
        <button type="submit">Login</button>
    </form>
</div>
