provider "aws" {
    region = "us-east-1"
}

variable "aws_security_group" {type = string}
variable "aws_subnet_ids" {type = list(string)}
variable "aws_vpc_id" {type = string}
variable "aws_ssl_certificate_arn" {type = string}
variable "aws_route53_zone_id" {type = string}
variable "aws_domain" {type = string}
variable "aws_subdomain" {type = string}
variable "aws_ecr_image" {
    type = string 
    default = "s3-dashboard:latest"
}

terraform {
    backend "s3" {}
}


#create ecr repository
resource "aws_ecr_repository" "ecr" {
    name = "s3-dashboard"
}

# output the repo url for the build pipeline
output "ecr_repository_url" {
    value = aws_ecr_repository.ecr.repository_url
}

# create ecs cluster
resource "aws_ecs_cluster" "cluster" {
    name = "s3-dashboard"
}

# create task definition
resource "aws_ecs_task_definition" "task" {
    family = "s3-dashboard"
    requires_compatibilities = ["FARGATE"]
    network_mode = "awsvpc"
    cpu = "256"
    memory = "512"
    depends_on = [aws_ecr_repository.ecr]
    container_definitions = jsonencode([
        {
            name = "s3-dashboard"
            image = var.aws_ecr_image
            essential = true
            portMappings = [
                {
                    containerPort = 80
                    hostPort = 80
                    protocol = "tcp"
                }
            ]
        }
    ])
}

# create target group for load balancer
resource "aws_lb_target_group" "tg" {
  name = "s3dashboard-tg"
  port = 80
  protocol = "HTTP"
  vpc_id = var.aws_vpc_id
  
  health_check {
    path = "/"
    interval = 30
    timeout = 5
    healthy_threshold = 2
    unhealthy_threshold = 2
  }
}

# create load balancer
resource "aws_lb" "lb" {
  name = "s3dashboard-lb"
  internal = false
  load_balancer_type = "application"
  security_groups = [var.aws_security_group]
  subnets = var.aws_subnet_ids
}

# create http listener for load balancer
resource "aws_lb_listener" "http-listener" {
  load_balancer_arn = aws_lb.s3dashboard-lb.arn
  port = 80
  protocol = "HTTP"

  default_action {
    type = "forward"
    target_group_arn = aws_lb_target_group.s3dashboard-tg.arn
  }
}

# create https listener for load balancer
resource "aws_lb_listener" "https-listener" {
  load_balancer_arn = aws_lb.s3dashboard-lb.arn
  port = 443
  protocol = "HTTPS"

  default_action {
    type = "forward"
    target_group_arn = aws_lb_target_group.s3dashboard-tg.arn
  }

  certificate_arn = var.aws_ssl_certificate_arn
  ssl_policy = "ELBSecurityPolicy-TLS13-1-3-2021-06"
}

# create ecs service
resource "aws_ecs_service" "service" {
    name = "s3-dashboard"
    cluster = aws_ecs_cluster.cluster.arn
    task_definition = aws_ecs_task_definition.task.arn
    desired_count = 1
    launch_type = "FARGATE"
    network_configuration {
        subnets = var.aws_subnet_ids
        security_groups = [var.aws_security_group]
        assign_public_ip = true
    }
    load_balancer {
        target_group_arn = aws_lb_target_group.s3dashboard-tg.arn
        container_name = "s3-dashboard"
        container_port = 8000
    }
}

# create route53 record for subdomain
resource "aws_route53_record" "subdomain" {
    zone_id = var.aws_route53_zone_id
    name = "${var.aws_subdomain}.${var.aws_domain}"
    type = "A"

    alias {
        name = aws_lb.lb.dns_name
        zone_id = aws_lb.lb.zone_id
        evaluate_target_health = true
    }
}