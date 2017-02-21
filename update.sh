#!/bin/bash

# Instead of 'cd /var/www/SinriLogKeeper;sudo git pull'
# USE 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'

echo SinriLogKeeper Instance on Gateway
ssh admin@10.24.253.187 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on ERP
ssh admin@10.25.171.8 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on ERP Brand
ssh admin@10.172.25.5 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on OMS Manager
ssh admin@10.27.14.146 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on OMS Server
ssh admin@10.25.175.227 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on Romeo
ssh admin@10.25.171.103 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on ERP Sync
ssh admin@10.25.56.74 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on Common Report
ssh admin@10.25.1.186 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on Finance Report
ssh admin@10.25.59.208 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on OC
ssh admin@10.25.70.99 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on BI
ssh admin@10.45.35.0 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on WMS Client
ssh admin@10.46.76.44 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on WMS
ssh admin@10.47.56.180 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on WMS Wai-I
ssh admin@10.26.23.201 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on WMS Wai-II
ssh admin@10.27.11.42 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on Web CRM
ssh admin@10.28.36.165 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on Test for Fundament
ssh admin@10.25.5.103 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on Test for OMS
ssh admin@10.25.58.198 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on Test for OMS Manager
ssh admin@10.26.255.156 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on Test for Web CRM
ssh admin@10.47.72.206 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on Test for WMS I
ssh admin@10.24.239.236 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on Test for WMS II
ssh admin@10.27.10.243 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on Express
ssh admin@10.27.2.29 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on JobCenter
ssh admin@10.27.233.59 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
