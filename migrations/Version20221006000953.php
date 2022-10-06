<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221006000953 extends AbstractMigration
{
    private array $products = [
        [
            'reference' => 'SHAREDDEBIAN',
            'name' => 'Debian mutualisé',
            'price' => 10,
            'description' => 'L\'un des systèmes Linux les plus faciles à utiliser',
            'type' => 'server',
            'server' => [
                'reference' => 'SHAREDDEBIAN',
                'operating_system' => 'Debian Buster',
                'memory' => 512,
                'server_type' => 'SHARED'
            ]
        ],
        [
            'reference' => 'VPSDEBIAN',
            'name' => 'Debian VPS',
            'price' => 8,
            'description' => 'L\'un des systèmes Linux les plus faciles à utiliser',
            'type' => 'server',
            'server' => [
                'reference' => 'VPSDEBIAN',
                'operating_system' => 'Debian Buster',
                'memory' => 512,
                'server_type' => 'VPS'
            ]
        ],
        [
            'reference' => 'VPSALPINE',
            'name' => 'Alpine VPS',
            'price' => 5,
            'description' => 'L\'un des systèmes Linux les plus légers et sécurisés',
            'type' => 'server',
            'server' => [
                'reference' => 'VPSALPINE',
                'operating_system' => 'Alpine',
                'memory' => 512,
                'server_type' => 'VPS'
            ]
        ],
        [
            'reference' => 'WORDPRESS',
            'name' => 'CMS Wordpress',
            'price' => 15,
            'description' => null,
            'type' => 'software',
            'software' => [
                'reference' => 'WORDPRESS',
                'version' => 6,
                'software_type' => 'CMS'
            ]
        ],
        [
            'reference' => 'PHP7.4',
            'name' => 'PHP 7.4',
            'price' => 10,
            'description' => null,
            'type' => 'software',
            'software' => [
                'reference' => 'PHP7.4',
                'version' => 7.4,
                'software_type' => 'Interpreter'
            ]
        ],
        [
            'reference' => 'PERL3',
            'name' => 'Perl 3',
            'price' => 2,
            'description' => null,
            'type' => 'software',
            'software' => [
                'reference' => 'PERL3',
                'version' => 3,
                'software_type' => 'Interpreter'
            ]
        ],
        [
            'reference' => 'GOLANG',
            'name' => 'Golang 1.19',
            'price' => 4,
            'description' => null,
            'type' => 'software',
            'software' => [
                'reference' => 'GOLANG',
                'version' => 1.19,
                'software_type' => 'Interpreter'
            ]
        ],
        [
            'reference' => 'PYTHON',
            'name' => 'Python',
            'price' => 10,
            'description' => null,
            'type' => 'software',
            'software' => [
                'reference' => 'PYTHON',
                'version' => 3,
                'software_type' => 'Interpreter'
            ]
        ],
        [
            'reference' => 'APACHE',
            'name' => 'Serveur Web Apache',
            'price' => 8,
            'description' => null,
            'type' => 'software',
            'software' => [
                'reference' => 'APACHE',
                'version' => 2.4,
                'software_type' => 'Web Server'
            ]
        ],
        [
            'reference' => 'NGINX',
            'name' => 'Serveur Web Nginx',
            'price' => 8,
            'description' => null,
            'type' => 'software',
            'software' => [
                'reference' => 'NGINX',
                'version' => 1.20,
                'software_type' => 'Web Server'
            ]
        ],
        [
            'reference' => 'CADDY',
            'name' => 'Serveur Web Caddy',
            'price' => 8,
            'description' => null,
            'type' => 'software',
            'software' => [
                'reference' => 'CADDY',
                'version' => 2.61,
                'software_type' => 'Web Server'
            ]
        ],
        [
            'reference' => 'BACKUP',
            'name' => 'Sauvegarde serveur',
            'price' => 4,
            'description' => null,
            'type' => 'option',
            'option' => [
                'reference' => 'BACKUP',
            ]
        ]
    ];

    public function getDescription(): string
    {
        return 'Creates some first products';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        foreach($this->products as $product)
        {

            $commonData = array_slice($product,0,-1);
            $this->addSql('INSERT INTO product VALUES (:reference,:name,:price,:description,:type)',$commonData);

            $specificData = array_slice($product,-1);

            if(key_exists('server',$specificData))
                $this->addSql('INSERT INTO server VALUES (:reference,:operating_system,:memory,:server_type)', $specificData['server']);
            elseif(key_exists('software',$specificData))
                $this->addSql('INSERT INTO software VALUES (:reference,:version,:software_type)',$specificData['software']);
            elseif(key_exists('option',$specificData))
                $this->addSql('INSERT INTO `option` VALUES (:reference)',$specificData['option']);


        }
    }

    public function down(Schema $schema): void
    {
        foreach($this->products as $product)
        {
            $this->addSql('DELETE FROM product WHERE reference = :reference',['reference' => $product['reference']]);

            if(key_exists('server',$product))
                $this->addSql('DELETE FROM server WHERE reference = :reference',['reference' => $product['reference']]);
            elseif(key_exists('software',$product))
                $this->addSql('DELETE FROM software WHERE reference = :reference',['reference' => $product['reference']]);
            elseif(key_exists('option',$product))
                $this->addSql('DELETE FROM `option` WHERE reference = :reference',['reference' => $product['reference']]);


        }
    }
}
