import React, { useState, useEffect } from 'react';
import axios from 'axios';
import {
    Typography,
    Box,
    Paper,
    List,
    ListItem,
    ListItemText,
    IconButton,
    TextField,
    Button,
    Dialog,
    DialogTitle,
    DialogContent,
    DialogActions,
    Snackbar,
    Alert,
} from '@mui/material';
import { Delete, Edit } from '@mui/icons-material';
// Optional: Import ReactMarkdown if using markdown rendering
// import ReactMarkdown from 'react-markdown';

const ChatHistory = () => {
    const [messages, setMessages] = useState([]);
    const [editMessage, setEditMessage] = useState(null);
    const [editUserInput, setEditUserInput] = useState('');
    const [snackbar, setSnackbar] = useState({ open: false, message: '', severity: 'success' });
    const [deleteMessage, setDeleteMessage] = useState(null);
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);

    useEffect(() => {
        fetchChatHistory();
    }, []);

    const fetchChatHistory = () => {
        axios
            .get('/api/v1/chat')
            .then((response) => {
                const data = Array.isArray(response.data) ? response.data : Object.values(response.data);
                setMessages(data);
            })
            .catch((error) => {
                console.error('Error fetching chat history:', error);
                setSnackbar({ open: true, message: 'Failed to fetch chat history.', severity: 'error' });
            });
    };

    const handleDelete = (message) => {
        setDeleteMessage(message);
        setIsDeleteDialogOpen(true);
    };

    const confirmDelete = () => {
        if (!deleteMessage) return;

        axios
            .delete(`/api/v1/chat/${deleteMessage.id}`)
            .then(() => {
                setMessages(messages.filter((msg) => msg.id !== deleteMessage.id));
                setSnackbar({ open: true, message: 'Message deleted successfully!', severity: 'success' });
            })
            .catch((error) => {
                console.error('Error deleting the message:', error);
                setSnackbar({ open: true, message: 'Failed to delete message.', severity: 'error' });
            })
            .finally(() => {
                setIsDeleteDialogOpen(false);
                setDeleteMessage(null);
            });
    };

    const handleEditOpen = (message) => {
        setEditMessage(message);
        setEditUserInput(message.user_input);
    };

    const handleEditClose = () => {
        setEditMessage(null);
        setEditUserInput('');
    };

    const handleEditSave = () => {
        axios
            .put(`/api/v1/chat/${editMessage.id}`, {
                user_input: editUserInput,
            })
            .then((response) => {
                const updatedMessage = response.data.data;
                setMessages(
                    messages.map((msg) =>
                        msg.id === updatedMessage.id ? updatedMessage : msg
                    )
                );
                setSnackbar({ open: true, message: 'Message updated successfully!', severity: 'success' });
                handleEditClose();
            })
            .catch((error) => {
                console.error('Error updating the message:', error);
                setSnackbar({ open: true, message: 'Failed to update message.', severity: 'error' });
            });
    };

    const handleCloseSnackbar = () => {
        setSnackbar({ ...snackbar, open: false });
    };

    return (
        <Box>
            <Typography variant="h4" gutterBottom>
                Chat History
            </Typography>
            <Paper style={{ padding: '1rem' }}>
                <List>
                    {messages.map((msg) => (
                        <ListItem key={msg.id} divider>
                            <ListItemText
                                primary={
                                    <Typography variant="subtitle1" fontWeight="bold">
                                        User: {msg.user_input}
                                    </Typography>
                                }
                                secondary={
                                    <Typography
                                        variant="body2"
                                        color="textSecondary"
                                        style={{ whiteSpace: 'pre-line' }}
                                    >
                                        AI: {msg.ai_response}
                                    </Typography>
                                    /*
                                    // Optional: Use ReactMarkdown for markdown formatting
                                    <Typography
                                        variant="body2"
                                        color="textSecondary"
                                        component="div"
                                    >
                                        <ReactMarkdown>
                                            {`AI:\n${msg.ai_response}`}
                                        </ReactMarkdown>
                                    </Typography>
                                    */
                                }
                            />
                            <IconButton
                                edge="end"
                                aria-label="edit"
                                onClick={() => handleEditOpen(msg)}
                            >
                                <Edit />
                            </IconButton>
                            <IconButton
                                edge="end"
                                aria-label="delete"
                                onClick={() => handleDelete(msg)}
                            >
                                <Delete />
                            </IconButton>
                        </ListItem>
                    ))}
                </List>
            </Paper>

            {/* Edit Dialog */}
            <Dialog
                open={!!editMessage}
                onClose={handleEditClose}
                maxWidth="sm"
                fullWidth={true}
                PaperProps={{
                    style: {
                        padding: '1rem',
                        borderRadius: '8px',
                        backgroundColor: '#ffffff',
                    },
                }}
            >
                <DialogTitle>Edit Message</DialogTitle>
                <DialogContent dividers>
                    <TextField
                        label="User Input"
                        variant="outlined"
                        fullWidth
                        margin="normal"
                        value={editUserInput}
                        onChange={(e) => setEditUserInput(e.target.value)}
                    />
                </DialogContent>
                <DialogActions>
                    <Button onClick={handleEditClose} color="secondary">
                        Cancel
                    </Button>
                    <Button
                        onClick={handleEditSave}
                        color="primary"
                        variant="contained"
                        disabled={!editUserInput.trim()}
                    >
                        Save
                    </Button>
                </DialogActions>
            </Dialog>

            {/* Delete Confirmation Dialog */}
            <Dialog
                open={isDeleteDialogOpen}
                onClose={() => setIsDeleteDialogOpen(false)}
                maxWidth="xs"
                fullWidth={true}
            >
                <DialogTitle>Confirm Deletion</DialogTitle>
                <DialogContent>
                    <Typography>
                        Are you sure you want to delete this message?
                    </Typography>
                </DialogContent>
                <DialogActions>
                    <Button onClick={() => setIsDeleteDialogOpen(false)} color="secondary">
                        Cancel
                    </Button>
                    <Button
                        onClick={confirmDelete}
                        color="primary"
                        variant="contained"
                    >
                        Delete
                    </Button>
                </DialogActions>
            </Dialog>

            {/* Snackbar for Notifications */}
            <Snackbar
                open={snackbar.open}
                autoHideDuration={6000}
                onClose={handleCloseSnackbar}
                anchorOrigin={{ vertical: 'bottom', horizontal: 'center' }}
            >
                <Alert onClose={handleCloseSnackbar} severity={snackbar.severity} sx={{ width: '100%' }}>
                    {snackbar.message}
                </Alert>
            </Snackbar>
        </Box>
    );

};

export default ChatHistory;
