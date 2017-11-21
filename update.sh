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
echo SinriLogKeeper Instance on Test for WMS BI
ssh admin@10.80.59.183 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'

echo SinriLogKeeper Instance on Express
echo " - EXPRESS-SERVICE-1"
ssh admin@10.27.2.29 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo " - EXPRESS-SYNC-1"
ssh admin@10.25.57.82 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo " - EXPRESS-ROUTE-1"
ssh admin@10.25.7.167 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'

echo SinriLogKeeper Instance on JobCenter
ssh admin@10.27.233.59 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on JobCenter2
ssh admin@10.24.233.245 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'

echo SinriLogKeeper Instance on WMSExpress
ssh admin@10.30.201.120 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo SinriLogKeeper Instance on TEST for ErpSync
ssh admin@10.27.62.30 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'

echo SinriLogKeeper Instance on OMS V2
echo omsv2_server
ssh admin@10.29.113.27 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo omsv2_job
ssh admin@10.28.146.205 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo omsv2_sync
ssh admin@10.29.199.85 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo omsv2_wc
ssh admin@10.30.200.71 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'
echo omsv2_web
ssh admin@10.29.192.50 'curl -L https://raw.githubusercontent.com/sinri/SinriLogKeeper/gateway/RefreshSinriLogKeeper.sh | bash'


