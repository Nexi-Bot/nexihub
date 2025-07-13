# Discord OAuth Setup Required

To complete the staff authentication system, you need to set up a Discord OAuth application:

## Steps:

1. **Go to Discord Developer Portal:**
   - Visit: https://discord.com/developers/applications
   - Log in with your Discord account

2. **Create New Application:**
   - Click "New Application"
   - Name it "Nexi Hub Staff Portal"
   - Click "Create"

3. **Configure OAuth2:**
   - Go to "OAuth2" tab in the left sidebar
   - Under "Redirects", add: `https://nexihub.uk/staff/discord-callback`
   - If testing locally, also add: `http://localhost/staff/discord-callback`

4. **Get Credentials:**
   - Copy the "Client ID" from the General Information tab
   - Copy the "Client Secret" from the General Information tab

5. **Update Configuration:**
   - Open `/config/config.php`
   - Replace `YOUR_DISCORD_CLIENT_ID` with your actual Client ID
   - Replace `YOUR_DISCORD_CLIENT_SECRET` with your actual Client Secret

## Current Configuration:
- **Redirect URI:** https://nexihub.uk/staff/discord-callback
- **Scopes:** identify (to get user info)

## Security Notes:
- Keep your Client Secret private
- Only add trusted redirect URIs
- The application should be set to "Bot" permissions: None (we only need OAuth2)

## Testing:
Once configured, staff can:
1. Click "Continue with Discord" on the login page
2. Authorize the application
3. Be redirected back to complete the login process

## Current Test Account:
- **Email:** ollie.r@nexihub.uk
- **Password:** test1212
- **2FA:** Will be set up on first login
