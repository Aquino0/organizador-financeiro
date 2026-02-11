import os
from PIL import Image

input_path = '/Users/aquino/Desktop/CRM FINANCEIRO/assets/logo.png'
output_path = '/Users/aquino/Desktop/CRM FINANCEIRO/assets/logo_social.png'

try:
    with Image.open(input_path) as img:
        # Resize maintaining aspect ratio
        img.thumbnail((500, 500))
        # Optimize and save as PNG
        img.save(output_path, 'PNG', optimize=True)
        print(f"Created {output_path}")
        
except Exception as e:
    print(f"Error: {e}")
