FROM alpine:3.17.0

RUN apk add --no-cache nodejs npm yarn

COPY entrypoint.sh /opt/entrypoint.sh

USER app

WORKDIR /opt/app

ENTRYPOINT ["/opt/entrypoint.sh"]
