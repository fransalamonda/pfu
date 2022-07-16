<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    /**
     * @var string
     */
    public $fromEmail;

    /**
     * @var string
     */
    public $fromName;

    /**
     * @var string
     */
    public $recipients;

    /**
     * The "user agent"
     *
     * @var string
     */
    public $userAgent = 'CodeIgniter';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     *
     * @var string
     */
    //     
    // public $SMTPHost = 'smtp.gmail.com';
    // public $SMTPUser = 'emailgmailanda@gmail.com';
    // public $SMTPPass = 'password gmail anda';
    // 
    // public $SMTPCrypto = 'ssl';
    // public $mailType = 'html';


    //    public $protocol = 'mail';
    public $protocol = 'smtp';
    public $SMTPHost = 'mail.cemindo.com';
    public $SMTPUser = 'digital.cg@cemindo.com';
    public $SMTPPass = 's6r3hN7G1!-Tn8h7';
    public $SMTPPort = 465;
    public $SMTPCrypto = 'ssl';
    public $mailType = 'html';

    /**
     * The server path to Sendmail.
     *
     * @var string
     */
    public $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Address
     *
     * @var string
     */
    //    public $SMTPHost;
    //    public $SMTPHost = 'mail.cemindo.com';

    /**
     * SMTP Username
     *
     * @var string
     */
    //public $SMTPUser;
    //public $SMTPUser = 'digital.cg@cemindo.com';

    /**
     * SMTP Password
     *
     * @var string
     */
    ////    public $SMTPPass;
    // public $SMTPPass = 's6r3hN7G1!-Tn8h7';

    /**
     * SMTP Port
     *
     * @var int
     */
    //    public $SMTPPort = 25;
    //public $SMTPPort = 465;

    /**
     * SMTP Timeout (in seconds)
     *
     * @var int
     */
    public $SMTPTimeout = 5;

    /**
     * Enable persistent SMTP connections
     *
     * @var bool
     */
    public $SMTPKeepAlive = false;

    /**
     * SMTP Encryption. Either tls or ssl
     *
     * @var string
     */
    //    public $SMTPCrypto = 'tls';
    //public $SMTPCrypto = 'ssl';
    /**
     * Enable word-wrap
     *
     * @var bool
     */
    public $wordWrap = true;

    /**
     * Character count to wrap at
     *
     * @var int
     */
    public $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     *
     * @var string
     */
    //    public $mailType = 'text';
    //public $mailType = 'html';
    /**
     * Character set (utf-8, iso-8859-1, etc.)
     *
     * @var string
     */
    public $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     *
     * @var bool
     */
    public $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     *
     * @var int
     */
    public $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     *
     * @var string
     */
    public $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     *
     * @var string
     */
    public $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     *
     * @var bool
     */
    public $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     *
     * @var int
     */
    public $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     *
     * @var bool
     */
    public $DSN = false;
}
