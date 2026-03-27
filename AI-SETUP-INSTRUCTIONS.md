# AI Assistant Setup Instructions

Your AI assistant is now configured to work with a free AI service! Follow these simple steps:

## Quick Setup (3 Steps)

### Step 1: Get a Free Hugging Face API Key
1. Go to https://huggingface.co/
2. Sign up for a free account (no credit card required)
3. Go to https://huggingface.co/settings/tokens
4. Click "New token" → Give it a name → Select "Read" access → Create
5. Copy your API key

### Step 2: Add Your API Key
1. Open the file `ai-proxy.php`
2. Find line 26: `$API_KEY = 'YOUR_HUGGINGFACE_API_KEY_HERE';`
3. Replace `YOUR_HUGGINGFACE_API_KEY_HERE` with your actual API key
4. Save the file

### Step 3: Upload to Your Web Server
Upload these files to your web hosting:
- `ai.html` (already exists)
- `ai-proxy.php` (new file)

**Important:** Your server must support PHP (most hosting providers do)

## That's It!

Your AI assistant will now respond with full product details instead of showing the offline message!

---

## How It Works

- The AI has complete knowledge of all your products (SWR Series, PP Fittings, Floor Traps, etc.)
- It can answer questions about specifications, weights, standards, dimensions
- It provides recommendations based on customer needs
- It directs customers to contact you for quotes and orders

---

## Testing Locally (Optional)

If you want to test on your computer before uploading:

1. Install a local PHP server:
   - **Windows:** Install XAMPP (https://www.apachefriends.org/)
   - **Mac:** PHP is pre-installed, just run: `php -S localhost:8000`

2. Place your files in the server directory

3. Open http://localhost:8000/ai.html in your browser

---

## Alternative: Other Free AI Services

If Hugging Face doesn't work for you, here are alternatives:

### Option A: OpenRouter (Free tier)
- Website: https://openrouter.ai/
- Free credits included
- Change line 24 in `ai-proxy.php` to:
  ```php
  $API_URL = 'https://openrouter.ai/api/v1/chat/completions';
  ```

### Option B: Groq (Fast & Free)
- Website: https://console.groq.com/
- Very fast responses
- Free tier available

---

## Troubleshooting

**Problem:** Still showing offline message
- Check that `ai-proxy.php` is in the same folder as `ai.html`
- Verify your API key is correct
- Check browser console (F12) for error messages

**Problem:** "Model is loading" error
- Hugging Face models need to "warm up" on first use
- Wait 20-30 seconds and try again
- The model will stay active after first use

**Problem:** PHP errors
- Make sure your hosting supports PHP 7.4 or higher
- Check that curl is enabled (most hosts have it by default)

---

## Need Help?

If you have any issues, check:
1. Browser console (Press F12 → Console tab)
2. PHP error logs on your server
3. Verify the API key is correctly pasted (no extra spaces)

---

## Security Note

The PHP proxy keeps your API key secure on the server side. Customers cannot see or steal your API key because it never gets sent to their browser.
