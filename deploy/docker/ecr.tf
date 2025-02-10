provider "aws" {
  region = "us-east-1"
}

terraform {
  backend "s3" {}
}

resource "aws_ecr_repository" "ecr" {
  name = "s3-dashboard"
}

output "ecr_repository_url" {
  value = aws_ecr_repository.ecr.repository_url
}