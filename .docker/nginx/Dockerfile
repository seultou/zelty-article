FROM nginx:1.23.2-alpine

COPY default.conf /etc/nginx/conf.d/
RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

EXPOSE 80
EXPOSE 443