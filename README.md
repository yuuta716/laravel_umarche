## ダウンロード⽅法
git clone 
https://github.com/aokitashipro/laravel_umarche.git
git clone ブランチを指定してダウンロードする場合 git clone -b ブランチ名
https://github.com/aokitashipro/laravel_umarche.git
もしくはzipファイルでダウンロードしてください
## インストール⽅法
cd laravel_umarche composer install npm install npm run dev
.env.example をコピーして .env ファイルを作成
.envファイルの中の下記をご利⽤の環境に合わせて変更してください。
DB_CONNECTION=mysql DB_HOST=127.0.0.1 DB_PORT=3306 DB_DATABASE=laravel_umarche 
DB_USERNAME=umarche DB_PASSWORD=password123
XAMPP/MAMPまたは他の開発環境でDBを起動した後に
php artisan migrate:fresh --seed
と実⾏してください。(データベーステーブルとダミーデータが追加されればOK)
最後に Php artisan key:generate と⼊⼒してキーを⽣成後、
Php artisan serve で簡易サーバーを⽴ち上げ、表⽰確認してください。
画像のダミーデータを扱う場合は php artisan storage:link でリンク作成し
public/images内の画像ファイルを storage/app/public内に shopsフォルダと productsフォルダを作成し そ
れぞれに画像をコピーしてください。
画像コピー後
php artisan migrate:fresh --seed と実施してください。

## インストール後の実施事項
画像のダミーデータはpublic/imagesフォルダ内にsample1.jpg 〜 sample6.jpgとして保存しています。 php 
artisan storage:linkでstorageフォルダにリンク後,
storage/app/public/productsフォルダ内に保存すると表⽰されます。

shopの画像を表⽰する場合はstorage/app/public/shopsフォルダを作成し画像を保存してください。
