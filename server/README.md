# PHP WhatsApp API Example

This folder contains a simple API server using `whatsapp-web.js` and a small PHP frontend.

## Usage

1. Install dependencies:
   ```
   npm install express
   ```

2. Start the API server:
   ```
   node server/api.js
   ```

3. Serve the PHP files using your preferred PHP server. The `php/admin` folder
   contains a small admin panel:
   - Visit `login.php` to authenticate a user and scan the QR code.
   - Once authenticated you will be redirected to `panel.php` where you can send
     messages using the API.
