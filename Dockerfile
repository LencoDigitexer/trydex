FROM alpine:3.16

RUN apk update && \
	apk --no-cache add \
		php php-fpm php-dom php-curl nginx\
		&& rm -rf /var/cache/apk/*

RUN echo "daemon off;" >> /etc/nginx/nginx.conf
COPY nginx.conf /etc/nginx/http.d/default.conf

COPY . /srv/librex
RUN rm /srv/librex/nginx.conf

RUN chown -R nginx: /srv/librex

EXPOSE 80
CMD php-fpm8 -D ; nginx