from loguru import logger
import boto3


def test():
    logger.info("Running test function")
    s3 = boto3.client("s3")
    response = s3.list_buckets()
    logger.info(f"Response: {response}")
    return response