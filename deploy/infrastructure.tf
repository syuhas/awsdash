provider "aws" {
  region = "us-east-1"
}

terraform {
  backend "s3" {
    bucket = "terraform-lock-bucket"
    key    = "s3dashboardphp/terraform.tfstate"
    region = "us-east-1"
    dynamodb_table = "terraform-lock-table"
  }
}

# create ec2 instance
resource "aws_instance" "s3-dashboard-php" {
  ami = "ami-06b21ccaeff8cd686"
  instance_type = "t2.micro"
  vpc_security_group_ids = ["sg-00d9ca388301c93a9"]
  key_name = "ec2"
  tags = {
    Name = "s3-dashboard-php"
  }
  iam_instance_profile = "php_demo_role"
  subnet_id = "subnet-0823df6c43b1a0ea4"
}

# create target group for load balancer
resource "aws_lb_target_group" "php-tg" {
  name = "php-tg"
  port = 80
  protocol = "HTTP"
  vpc_id = "vpc-0fb8abe9d3c477bd0"
  
  health_check {
    path = "/"
    interval = 30
    timeout = 5
    healthy_threshold = 2
    unhealthy_threshold = 2
  }
}

# create load balancer
resource "aws_lb" "php-lb" {
  name = "php-lb"
  internal = false
  load_balancer_type = "application"
  security_groups = ["sg-00d9ca388301c93a9"]
  subnets = ["subnet-0823df6c43b1a0ea4", "subnet-057dcb202796ed034"]
}

# create http listener for load balancer
resource "aws_lb_listener" "php-http-lb-listener" {
  load_balancer_arn = aws_lb.php-lb.arn
  port = 80
  protocol = "HTTP"

  default_action {
    type = "forward"
    target_group_arn = aws_lb_target_group.php-tg.arn
  }
}

# create https listener for load balancer
resource "aws_lb_listener" "php-https-lb-listener" {
  load_balancer_arn = aws_lb.php-lb.arn
  port = 443
  protocol = "HTTPS"

  default_action {
    type = "forward"
    target_group_arn = aws_lb_target_group.php-tg.arn
  }

  certificate_arn = "arn:aws:acm:us-east-1:551796573889:certificate/a96258a9-2996-4186-b5a8-815bf9c5b3e1"
  ssl_policy = "ELBSecurityPolicy-TLS13-1-3-2021-06"
}

# register instance with the target group
resource "aws_lb_target_group_attachment" "php-tg-attachment" {
  target_group_arn = aws_lb_target_group.php-tg.arn
  target_id = aws_instance.s3-dashboard-php.id
  port = 80
}

resource "aws_route53_record" "php-dns" {
  zone_id = "Z02299283BLAIJGG9JHMK"
  name = "php.digitalsteve.net"
  type = "A"

  alias {
    name = aws_lb.php-lb.dns_name
    zone_id = aws_lb.php-lb.zone_id
    evaluate_target_health = true
  }
}

output "instance_id" {
  value = aws_instance.s3-dashboard-php.id
}

output "instance_public_ip" {
  value = aws_instance.s3-dashboard-php.public_ip
}

output "instance_public_dns" {
  value = aws_instance.s3-dashboard-php.public_dns
}

output "instance_name" {
  value = aws_instance.s3-dashboard-php.tags.Name  
}
