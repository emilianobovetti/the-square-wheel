FROM alpine:3.17.0 as builder

RUN apk add --no-cache util-linux nodejs npm yarn

FROM builder AS dev

ARG UID=1000
ARG GID=1000

RUN addgroup -S -g "$GID" app && \
  adduser -S -u "$UID" -G app app

COPY entrypoint.sh /opt/entrypoint.sh

USER app

WORKDIR /opt/app

ENTRYPOINT ["/opt/entrypoint.sh"]
