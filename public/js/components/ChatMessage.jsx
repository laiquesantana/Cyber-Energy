
import React from 'react';

const ChatMessage = ({ message, sender }) => {
    return (
        <div className={`chat-message ${sender.toLowerCase()}`}>
            <strong>{sender}:</strong> <span>{message}</span>
        </div>
    );
};

export default ChatMessage;
