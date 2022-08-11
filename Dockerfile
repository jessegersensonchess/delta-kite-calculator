FROM golang:alpine3.15
COPY . ./
RUN CGO_ENABLED=0 go build delta-kite-calculator.go
CMD [ "./delta-kite-calculator" ]
