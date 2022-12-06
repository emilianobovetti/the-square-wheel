FROM alpine:3.17.0

ARG UID=1000
ARG GID=1000

RUN apk add --no-cache util-linux nodejs npm yarn && \
  addgroup -S -g "$GID" app && \
  adduser -S -u "$UID" -G app app

COPY entrypoint.sh /opt/entrypoint.sh

USER app

WORKDIR /opt/app

ENTRYPOINT ["/opt/entrypoint.sh"]
