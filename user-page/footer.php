<footer class="main-footer" style="background:#fdf6f4; padding:60px 0 30px; border-top:1px solid #eee; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #555;">
    <div class="container" style="max-width: 1100px; margin: 0 auto; display: flex; flex-wrap: wrap; justify-content: space-between; text-align: left; padding: 0 20px;">
        
        <!-- Brand Section -->
        <div style="flex: 1; min-width: 250px; margin-bottom: 30px;">
            <h3 style="color: #D9A899; margin-bottom: 15px; font-size: 1.5rem;">Shivi's Stylevana</h3>
            <p style="font-size: 14px; line-height: 1.6; color: #777;">Curating the finest essentials for your lifestyle. Quality meets elegance in every piece.</p>
            <div style="margin-top: 20px;">
                <a href="#" style="margin-right: 15px; color: #D9A899; font-size: 20px;"><i class="fab fa-instagram"></i></a>
                <a href="#" style="margin-right: 15px; color: #D9A899; font-size: 20px;"><i class="fab fa-facebook-f"></i></a>
                <a href="#" style="color: #D9A899; font-size: 20px;"><i class="fab fa-pinterest"></i></a>
            </div>
        </div>

        <!-- Quick Links -->
        <div style="flex: 0.5; min-width: 150px; margin-bottom: 30px;">
            <h4 style="color: #444; margin-bottom: 15px; font-size: 1rem; text-transform: uppercase;">Shop</h4>
            <ul style="list-style: none; padding: 0; font-size: 14px; line-height: 2;">
                <li><a href="#" style="text-decoration: none; color: #777;">New Arrivals</a></li>
                <li><a href="#" style="text-decoration: none; color: #777;">Best Sellers</a></li>
                <li><a href="#" style="text-decoration: none; color: #777;">All Products</a></li>
            </ul>
        </div>

        <!-- Support -->
        <div style="flex: 0.5; min-width: 150px; margin-bottom: 30px;">
            <h4 style="color: #444; margin-bottom: 15px; font-size: 1rem; text-transform: uppercase;">Customer Care</h4>
            <ul style="list-style: none; padding: 0; font-size: 14px; line-height: 2;">
                <li><a href="#" style="text-decoration: none; color: #777;">Shipping Policy</a></li>
                <li><a href="#" style="text-decoration: none; color: #777;">Returns & Exchanges</a></li>
                <li><a href="#" style="text-decoration: none; color: #777;">Contact Us</a></li>
            </ul>
        </div>

        <!-- Newsletter -->
        <div style="flex: 1; min-width: 250px; margin-bottom: 30px;">
            <h4 style="color: #444; margin-bottom: 15px; font-size: 1rem; text-transform: uppercase;">Stay Updated</h4>
            <p style="font-size: 13px; margin-bottom: 15px;">Subscribe to get special offers and first looks.</p>
            <div style="display: flex;">
                <input type="email" placeholder="Email address" style="padding: 10px; border: 1px solid #ddd; border-right: none; outline: none; flex: 1;">
                <button style="background: #D9A899; color: white; border: none; padding: 10px 15px; cursor: pointer;">Join</button>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div style="border-top: 1px solid #f0e0db; margin-top: 40px; padding-top: 20px; font-size: 12px; color: #999;">
        <p>&copy; 2026 Shivi's Stylevana. Crafted with care.</p>
    </div>
</footer>



<!-- ========================= -->
<!-- STYLEVANA AI CHAT -->
<!-- ========================= -->

<div id="stylevana-chat" style="
position:fixed;
bottom:20px;
right:20px;
z-index:10000;
font-family:'Poppins', sans-serif;
">

    <!-- CHAT WINDOW -->
    <div id="ai-window" style="
    display:none;
    width:340px;
    height:480px;
    background:#fff;
    border-radius:18px;
    box-shadow:0 10px 35px rgba(0,0,0,0.15);
    flex-direction:column;
    overflow:hidden;
    border:1px solid #eee;
    margin-bottom:15px;
    ">

        <!-- HEADER -->
        <div style="
        background:#D9A899;
        color:white;
        padding:16px;
        font-weight:600;
        display:flex;
        justify-content:space-between;
        align-items:center;
        font-size:15px;
        ">

            <span>Stylevana AI</span>

            <button onclick="toggleChat()" style="
            background:none;
            border:none;
            color:white;
            cursor:pointer;
            font-size:22px;
            ">
            &times;
            </button>

        </div>


        <!-- CHAT LOGS -->
        <div id="ai-logs" style="
        flex:1;
        padding:15px;
        overflow-y:auto;
        background:#fcf9f8;
        display:flex;
        flex-direction:column;
        gap:12px;
        ">

            <!-- WELCOME MESSAGE -->
            <div style="
            background:#f4ebe4;
            color:#000;
            padding:12px;
            border-radius:14px 14px 14px 4px;
            align-self:flex-start;
            max-width:80%;
            font-size:14px;
            line-height:1.6;
            ">
            Hello! Welcome to Stylevana. How can I help you today?
            </div>

        </div>


        <!-- INPUT AREA -->
        <div style="
        padding:12px;
        border-top:1px solid #eee;
        display:flex;
        gap:10px;
        background:#fff;
        ">

            <input
                type="text"
                id="ai-input"
                placeholder="Type message..."
                style="
                flex:1;
                border:none;
                outline:none;
                font-size:14px;
                padding:12px;
                background:#f5f5f5;
                border-radius:10px;
                color:#000;
                "
            >

            <button onclick="sendToAI()" style="
            border:none;
            background:#D9A899;
            color:white;
            font-weight:600;
            cursor:pointer;
            padding:0 18px;
            border-radius:10px;
            ">
            Send
            </button>

        </div>

    </div>


    <!-- FLOAT BUTTON -->
    <button onclick="toggleChat()" style="
    width:62px;
    height:62px;
    border-radius:50%;
    background:#D9A899;
    color:white;
    border:none;
    cursor:pointer;
    font-size:24px;
    box-shadow:0 4px 15px rgba(217,168,153,0.5);
    display:flex;
    align-items:center;
    justify-content:center;
    ">
    💬
    </button>

</div>



<script>

// =========================
// OPEN / CLOSE CHAT
// =========================
function toggleChat() {

    let win = document.getElementById('ai-window');

    win.style.display =
        (win.style.display === 'none' || win.style.display === '')
        ? 'flex'
        : 'none';
}


// =========================
// SEND MESSAGE
// =========================
async function sendToAI() {

    let input = document.getElementById('ai-input');

    let logs = document.getElementById('ai-logs');

    let msg = input.value.trim();

    if (!msg) return;


    // USER MESSAGE
    logs.innerHTML += `
    <div style="
    background:#D9A899;
    color:white;
    padding:12px;
    border-radius:14px 14px 4px 14px;
    align-self:flex-end;
    max-width:80%;
    font-size:14px;
    line-height:1.5;
    word-wrap:break-word;
    ">
    ${msg}
    </div>
    `;

    input.value = "";

    logs.scrollTop = logs.scrollHeight;


    try {

        // SEND TO PHP
        const response = await fetch('chat_helper.php', {

            method:'POST',

            headers:{
                'Content-Type':'application/json'
            },

            body:JSON.stringify({
                message:msg
            })

        });


        // SERVER ERROR
        if (!response.ok) {

            throw new Error('Server Error');

        }


        const data = await response.json();


        // CLEAN REPLY
        let cleanReply = data.reply
            .replace(/[*#`]/g, '')
            .replace(/\n/g, '<br>');


        // AI MESSAGE
        logs.innerHTML += `
        <div style="
        background:#f4ebe4;
        color:#000;
        padding:12px;
        border-radius:14px 14px 14px 4px;
        align-self:flex-start;
        max-width:80%;
        font-size:14px;
        line-height:1.6;
        word-wrap:break-word;
        ">
        ${cleanReply}
        </div>
        `;


    } catch (e) {

        console.error("Chat Error:", e);

        logs.innerHTML += `
        <div style="
        color:red;
        font-size:12px;
        text-align:center;
        ">
        Connection Error
        </div>
        `;
    }

    logs.scrollTop = logs.scrollHeight;
}


// =========================
// ENTER KEY SUPPORT
// =========================
document.getElementById('ai-input').addEventListener('keypress', function(e){

    if(e.key === 'Enter') {

        sendToAI();

    }

});

</script>