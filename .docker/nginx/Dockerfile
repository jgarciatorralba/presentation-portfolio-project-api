FROM nginx:1.28.0-alpine

COPY nginx.conf /etc/nginx/
COPY templates /etc/nginx/templates/
RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

EXPOSE 80
EXPOSE 443
