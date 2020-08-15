<?php


interface Interface_signTable{
    
    
    // Предназначен для создания записи в таблице подписей
    //
    static public function create(int $id_sign,
                                  int $is_external,
                                  ?int $id_file,
                                  string $fio,
                                  string $certificate,
                                  string $signature_result,
                                  string $signature_message,
                                  string $signature_user_message,
                                  string $certificate_result,
                                  string $certificate_message,
                                  string $certificate_user_message):int;
    
}