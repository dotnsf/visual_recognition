# Visual Recognition

PHP + MySQL + Visual Recognition(V2) による簡易画像アップローダーサンプル


## 準備

- PHP + MySQL + httpd 環境の構築

## ファイル

- createtables.php : 必要なテーブルを作成する（最初に一回実行）

- credentials.php : 接続情報（このファイルをカスタマイズする必要有り）

- delete.php : 指定した画像の情報を DB から削除する

- image.php : 個別の画像とそのメタ情報を出力するページ（メインページからリンク）

- index.php : 画像一覧を出力するページ（メインページ）

- loadimg.php : 画像バイナリを出力する

- up.php : アップロードされた画像バイナリを受け取って DB に格納する

- uptest.html : アップロードテスト用

- upload.lua : LUA によるアップロードスクリプト

- composer.json : IBM Bluemix 用

- .bp-config/options.json : IBM Bluemix 用

## 準備


- LAMP 環境を用意
 * IBM Bluemix であれば PHP ランタイムと ClearDB サービスをバインドする

- credentials.php ファイル内の MySQL 接続情報および AlchemyAPI のキー情報を更新
- HTML, PHP ファイル全てを PHP アプリケーションサーバーのドキュメントルートにデプロイ
 * IBM Bluemix であれば、HTML, PHP ファイル全てを PHP ランタイムにプッシュ

- アプリケーションサーバー上の createtables.php をブラウザから実行して、必要なテーブルを作成する

- LUA ファイルを FlashAir 内にコピー
 * 例えば FlashAir 内の \lua\upload.lua にコピー
 * upload.lua ファイル内のアップロード先サーバー（L.26）を実際のアプリケーションサーバーになるよう書き換え

- FlashAir の SD_WLAN\config ファイルをテキストエディタで編集
 * APPMODE = 5 を指定（WiFi 子機）
 * APPSSID, APPNETWORKKEY で SSID とパスフレーズを指定
 * LUA_SD_EVENT=/lua/upload.lua を追加

## 使い方

- 最初に一回だけ createtables.php をブラウザから呼び出して実行

- ブラウザでアプリケーションサーバーのドキュメントルートにアクセス（何も画像が登録されていないことを確認）

- SD カードに新しい画像をコピー（新しく撮影したイメージ）

- 追加された画像が自動的にサーバーに転送され、AlchemyAPI でタグ付けされる

- 改めてブラウザでアプリケーションサーバーのドキュメントルートにアクセスすると、追加した画像が一覧に含まれていて、クリックするとメタ情報と一緒に表示されることを確認する。

## 開発者

- K.Kimura ( dotnsf@gmail.com ), all rights reserved.


