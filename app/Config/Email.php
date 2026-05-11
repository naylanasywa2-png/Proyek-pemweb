<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'punyakega3@gmail.com';
    public string $fromName   = 'Sistem Digital Memories';
    public string $recipients = '';
    public string $userAgent  = 'CodeIgniter';

    // Pengaturan Utama (Sudah Diperbaiki)
    public string $protocol   = 'smtp'; 
    public string $SMTPHost   = 'smtp.googlemail.com';
    public string $SMTPUser   = 'punyakega3@gmail.com'; 
    public string $SMTPPass   = 'yqyc rauk pqkx bmsq'; 
    public int $SMTPPort      = 465;
    public string $SMTPCrypto = 'ssl'; 
    public string $mailType   = 'html';

    public string $mailPath       = '/usr/sbin/sendmail';
    public string $SMTPAuthMethod = 'login';
    public int $SMTPTimeout       = 5;
    public bool $SMTPKeepAlive    = false;
    public bool $wordWrap         = true;
    public int $wrapChars         = 76;
    public string $charset        = 'UTF-8';
    public bool $validate         = false;
    public int $priority          = 3;
    public string $CRLF           = "\r\n";
    public string $newline        = "\r\n";
    public bool $BCCBatchMode     = false;
    public int $BCCBatchSize      = 200;
    public bool $DSN              = false;
}