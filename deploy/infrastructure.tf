provider "aws" {
  region = "us-east-1"
}

terraform {
  backend "s3" {
    bucket = "terraform-lock-bucket"
    key    = "dashboard/terraform.tfstate"
    region = "us-east-1"
    dynamodb_table = "terraform-lock-table"
  }
}

resource "aws_instance" "testinstance" {
  ami = "ami-06b21ccaeff8cd686"
  instance_type = "t2.micro"
  vpc_security_group_ids = ["sg-00d9ca388301c93a9"]
  user_data = file("userdata.sh")
  key_name = "ec2"
  tags = {
    Name = "testinstance"
  }
}

output "instance_id" {
  value = aws_instance.testinstance.id
}

output "instance_public_ip" {
  value = aws_instance.testinstance.public_ip
}

output "instance_public_dns" {
  value = aws_instance.testinstance.public_dns
}

output "instance_name" {
  value = aws_instance.testinstance.tags.Name  
}

