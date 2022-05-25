<?php

/*
 * Author Thomas Beauchataud
 * Since 16/05/2022
 */

namespace TBCD\FtpClientBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use TBCD\FtpClient\FtpClient;
use TBCD\FtpClient\FtpsClient;
use TBCD\FtpClient\ScpClient;
use TBCD\FtpClient\SftpClient;

class FtpClientExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $configs = $this->processConfiguration($configuration, $configs);

        $defaultsConfig = $configs['defaults'];
        foreach ($configs['clients'] as $clientName => $clientConfig) {

            if (isset($clientConfig['default'])) {
                if (!isset($defaultsConfig[$clientConfig['default']])) {
                    throw new \Exception('The default config ' . $clientConfig['default'] . "doesn't exists");
                }
                $clientConfig = array_merge($defaultsConfig[$clientConfig['default']], $clientConfig);
            }

            $definition = $this->buildDefinition($clientConfig);
            $container->setDefinition("tbcd.ftp_client.$clientName", $definition);
            $container->setAlias("$clientName.ftp_client", "tbcd.ftp_client.$clientName")
                ->setPublic("$clientName.ftp_client");
        }
    }

    private function buildDefinition(array $clientConfig): Definition
    {
        $arguments = [$clientConfig['host'], $clientConfig['user'], $clientConfig['credentials'], $clientConfig['port']];

        if ($clientConfig['protocol'] === 'ftp') {
            $class = FtpClient::class;
            if (isset($clientConfig['passive'])) {
                $arguments[] = $clientConfig['passive'];
            }
            if (isset($clientConfig['keepAlive'])) {
                $arguments[] = $clientConfig['keepAlive'];
            }

        } else if ($clientConfig['protocol'] === 'ftps') {
            $class = FtpsClient::class;
            if (isset($clientConfig['passive'])) {
                $arguments[] = $clientConfig['passive'];
            }
            if (isset($clientConfig['keepAlive'])) {
                $arguments[] = $clientConfig['keepAlive'];
            }

        } else if ($clientConfig['protocol'] === 'sftp') {
            $class = SftpClient::class;
            if (isset($clientConfig['keepAlive'])) {
                $arguments[] = $clientConfig['keepAlive'];
            }

        } else if ($clientConfig['protocol'] === 'scp') {
            $class = ScpClient::class;
            if (isset($clientConfig['keepAlive'])) {
                $arguments[] = $clientConfig['keepAlive'];
            }

        } else {
            throw new \Exception("The protocol " . $clientConfig['protocol'] . "is not valid");
        }

        return new Definition($class, $arguments);
    }
}