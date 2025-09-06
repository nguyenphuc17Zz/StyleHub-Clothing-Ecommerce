import { useState, useEffect, useRef, useMemo } from "react";
import { chatApi } from "../../api/chatApi";
import "./Chat.css";
import {
  getTokenLocalStorage,
  getTokenSessionStorage,
} from "../../helper/helper";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

function Chat() {
  window.Pusher = Pusher;
  const token = getTokenLocalStorage() || getTokenSessionStorage();

  const echo = useMemo(() => {
    if (!token) return null;
    return new Echo({
      broadcaster: "pusher",
      key: "fa90d07fd875d84b37b8",
      cluster: "ap1",
      forceTLS: true,
      authEndpoint: "http://127.0.0.1:8000/api/broadcasting/auth",
      auth: {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    });
  }, [token]);

  useEffect(() => {
    // Bắt các sự kiện kết nối / lỗi
    echo.connector.pusher.connection.bind("connected", () => {
      console.log("✅ Pusher connected");
    });

    echo.connector.pusher.connection.bind("error", (err) => {
      console.error("❌ Pusher connection error:", err);
    });

    echo.connector.pusher.connection.bind("failed", () => {
      console.error("❌ Pusher connection failed");
    });
  }, [echo]);

  const [messages, setMessages] = useState([]);
  const [input, setInput] = useState("");
  const [chatId, setChatId] = useState(null);
  const messagesEndRef = useRef(null);

  const fetchMessage = async () => {
    try {
      const res = await chatApi.getOrCreateChat();
      if (res.success) {
        setMessages(res.messages);
        setChatId(Number(res.chat.id));
        scrollToBottom();
      }
    } catch (err) {
      console.error("Lỗi lấy messages:", err);
    }
  };

  useEffect(() => {
    fetchMessage();
  }, []);

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  };

  const handleSend = async () => {
    if (!input || !chatId) return;
    const data = {
      sender: "user",
      input: input,
    };
    try {
      const message = await chatApi.sendMessage(data, chatId);
      //setMessages((prev) => [...prev, message]);

      setInput("");
      scrollToBottom();
    } catch (err) {
      console.error("Gửi tin nhắn lỗi:", err);
    }
  };

  useEffect(() => {
    if (!chatId) return;

    const channel = echo.private(`chat.${chatId}`);
    channel.subscribed(() => {
      console.log(`✅ Subscribed to private channel chat.${chatId}`);
    });

    channel.listen(".NewMessage", (e) => {
      console.log("📩 Nhận event:", e);
      setMessages((prev) => [...prev, e.message]);
      scrollToBottom();
    });

    return () => {
      echo.leave(`chat.${chatId}`);
    };
  }, [chatId, echo]);

  return (
    <div className="chat-container">
      <div className="messages">
        {messages.map((msg) => (
          <div
            key={msg.id}
            className={`message ${msg.sender === "user" ? "me" : "other"}`}
          >
            {msg.content}
          </div>
        ))}
        <div ref={messagesEndRef} />
      </div>

      <div className="input-box">
        <input
          type="text"
          value={input}
          onChange={(e) => setInput(e.target.value)}
          placeholder="Nhập tin nhắn..."
        />
        <button onClick={handleSend}>Gửi</button>
      </div>
    </div>
  );
}

export default Chat;
