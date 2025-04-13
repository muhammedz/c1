<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Tümünü getir
     * 
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*']);
    
    /**
     * Sayfalandırılmış veri getir
     * 
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = ['*']);
    
    /**
     * ID'ye göre bul
     * 
     * @param int $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*']);
    
    /**
     * Belirli alan değerine göre bul
     * 
     * @param string $field
     * @param mixed $value
     * @param array $columns
     * @return mixed
     */
    public function findByField($field, $value, $columns = ['*']);
    
    /**
     * Yeni kayıt oluştur
     * 
     * @param array $data
     * @return mixed
     */
    public function create(array $data);
    
    /**
     * ID'ye göre güncelle
     * 
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, $id);
    
    /**
     * ID'ye göre sil
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id);
} 