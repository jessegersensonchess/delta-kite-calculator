FROM golang:alpine3.15
COPY . ./
RUN go build delta-kite-calculator.go
CMD [ "./delta-kite-calculator" ]
