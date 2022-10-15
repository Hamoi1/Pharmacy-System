
## **Install step**

- git clone **https://github.com/Hamoi1/Pharmacy-System.git**
- go to the dirctory **cd Pharmacy-System**
- copy **.env.example to .env** like this <b>copy cp .env.example .env</b>
- open project in your Editor
- go to app/Providers/AppServiceProvider.php 
- comment this line <b> view()->share('settings', \App\Models\Settings::first())</b>
- run **composer update**
- run **php artisan key:generate**
- run **php artisan migrate --seed**
- remove comment this line <b> view()->share('settings', \App\Models\Settings::first())</b>
- run **php artisan serve**

## Email Account 
<p>
<p>
Email : 
<b>
ihama9728@gmail.com
</b>
</p>
<p>
Password :
<b>
muhammad
</b>
</p>
</p>
