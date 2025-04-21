-- Öncelikle mevcut URL'leri yedekleyelim
ALTER TABLE filemanagersystem_medias ADD COLUMN original_url VARCHAR(255) NULL;
UPDATE filemanagersystem_medias SET original_url = url WHERE url LIKE '%localhost%';

-- URL'leri düzeltelim
UPDATE filemanagersystem_medias 
SET url = REPLACE(url, 'http://localhost:8000', 'https://cankaya.epoxsoft.net.tr') 
WHERE url LIKE '%localhost%';

-- WebP URL'lerini de düzeltelim
UPDATE filemanagersystem_medias 
SET webp_url = REPLACE(webp_url, 'http://localhost:8000', 'https://cankaya.epoxsoft.net.tr') 
WHERE webp_url LIKE '%localhost%';

-- Başka tablolardaki görsel URL'lerini de güncellememiz gerekebilir
-- Örneğin slider tablosunda
UPDATE sliders
SET filemanagersystem_image_url = REPLACE(filemanagersystem_image_url, 'http://localhost:8000', 'https://cankaya.epoxsoft.net.tr')
WHERE filemanagersystem_image_url LIKE '%localhost%'; 