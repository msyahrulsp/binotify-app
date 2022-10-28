 ## Binotify
Sebuah website luar biasa saja yang dikerjakan oleh 3 mahasiswa relatif stress yang berfungsi sebagai pemutar lagu (walaupun Spotify lebih bagus) untuk memenuhi salah satu Tugas Besar pada mata kuliah Web Based Development

## Requirement
Untuk menjalankan website ini, yang dibutuhkan hanyalah [Docker](https://docs.docker.com/desktop/)

## How to Install
- Pastikan sudah ada [Git](https://git-scm.com/)
- Clone repo ini
```
git clone https://gitlab.informatika.org/if3110-2022-k02-01-13/tugas-besar-1.git binotify
cd binotify
```

## How to Run
- Ubah .env.example menjadi .env
- Pastikan sudah menginstall [Docker](https://docs.docker.com/desktop/)
- Pastikan sudah ada di folder `binotify`
- Jalankan perintah `docker-compose up`

Terdapat 2 link yang bisa diakses langsung, berikut link tersebut:
- Page akan berjalan pada `localhost:8080`
- phpmyadmin akan berjalan pada `localhost:8081`
> username dan password menyesuaikan dengan isi `docker-compose.yml`. Dalam kasus ini `root` dan `123456`

## Screenshot Halaman
### Login
![image](https://user-images.githubusercontent.com/69589003/198531250-3613336d-bcac-4c06-9762-942d888df272.png)
### Register
![image](https://user-images.githubusercontent.com/69589003/198531415-976809a2-ba78-44a1-80d7-f442b34ab488.png)
### Home User
![image](https://user-images.githubusercontent.com/69589003/198531586-6ccd201f-b268-460a-8d7c-ef733262e76b.png)
### Home Admin
![image](https://user-images.githubusercontent.com/69589003/198531702-c89752b8-38aa-472b-b992-de6f5b813ab1.png)
### Daftar Album
![image](https://user-images.githubusercontent.com/69589003/198531799-bd727b3b-f769-4783-8d94-c0387ffc3858.png)
### Search, Sort, Filter
![image](https://user-images.githubusercontent.com/69589003/198531917-0a1a7945-23db-4147-aefd-5ec407ebfd7a.png)
### Detail Lagu (Admin)
![image](https://user-images.githubusercontent.com/69589003/198532037-7543b788-a82f-48bd-a439-6fa506edb0c5.png)
### Edit Lagu (Admin)
![image](https://user-images.githubusercontent.com/69589003/198532614-7d61d2c5-37d2-4c6d-a64e-cb2a7ca52bda.png)
### Detail Album (Admin)
![image](https://user-images.githubusercontent.com/69589003/198532759-62c5ac09-6d39-4ed0-8c53-13ff070c4b85.png)
### Tambah Album
![image](https://user-images.githubusercontent.com/69589003/198532847-5217718a-2c63-4b14-b3b5-22bbf77f488f.png)
### Tambah Lagu
![image](https://user-images.githubusercontent.com/69589003/198532922-9ca64d17-9a7e-4ae3-a6b0-ba0f231f12fe.png)
### Daftar User
![image](https://user-images.githubusercontent.com/69589003/198533069-2075c076-742a-4869-945c-93c5ddcd934d.png)


## Pembagian Tugas 
Server-side  
| Task        | NIM           |
| ------------- |-------------|
|Home            | 13520002, 13520002, 13520080  |
|Login           | 13520080  |
|Register        | 13520080  |
|Search          | 13520080  |
|Search Filter   | 13520080  |
|Search Pagination   | 13520080  |
|User List       | 13520080  |
|Album List      | 13520161  |
|Detail Album    | 13520161  |
|Add Album    | 13520161  |
|Edit Album      | 13520161  |
|Delete Album    | 13520161  |
|Song List       | 13520002  |
|Detail Song     | 13520002  |
|Add Song     | 13520002  |
|Edit Song       | 13520002  |
|Delete Song     | 13520002  |
|Song Limit Per Day | 13520002  |
|Access Level | 13520002, 13520002, 13520080  |
|Setup Database           | 13520080  |
|Setup Docker    | 13520161  |
  
Client-side  
| Task        | NIM           |
| ------------- |-------------|
|Home            | 13520002, 13520002, 13520080  |
|Login           | 13520080  |
|Register        | 13520080  |
|Search          | 13520080  |
|Search Filter   | 13520080  |
|Search Pagination   | 13520080  |
|User List       | 13520080  |
|Album List      | 13520161  |
|Detail Album    | 13520161  |
|Add Album    | 13520161  |
|Edit Album      | 13520161  |
|Delete Album    | 13520161  |
|Song List       | 13520002  |
|Detail Song     | 13520002  |
|Add Song     | 13520002  |
|Edit Song       | 13520002  |
|Delete Song     | 13520002  |
|Song Limit Per Day | 13520002  |
|Access Level | 13520002, 13520002, 13520080  |
