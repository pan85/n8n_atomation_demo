## N8N Automation
Instalation
- cp .env.example .env and change needed values etc. passwords, keys,STORE_BEARER_TOKEN,N8N_BEARER_TOKEN, all notSecureReplaceMe values. 
Have in mind that code will work as it is, but won't be secure. 
- make ssh to connect to server
- php artisan key:generate to
- php artisan migrate --force

Use [AdScriptN8n.json](AdScriptN8n.json) to import workflow to N8N on http://localhost:5678
