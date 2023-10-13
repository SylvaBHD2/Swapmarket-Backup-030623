# INSERT INTO `post` (`post_id`, `date_post`, `model_name`, `post_type`, `price`, `size`, `used`, `extreme_price`, `int_interactions`, `post_state`, `model_id`, `photo`, `user_id`) VALUES ('1485615', CURRENT_TIMESTAMP, 'Patta x Air Jordan 7 Retro OG SP Shimmer', 'Vend', '180', '47', 'Yes', '150', NULL, NULL, '67852', 'Patta x Air Jordan 7 Retro OG SP \'Shimmer\'.png', '1186292008');

user_ids = [1586473058,327367553,881293111,456453966,1186292008]

post_types = ['Vend', 'Achète']

# price is between 30 and 300
# size is between 33 and 47
used_list = ['Yes', 'No']

# only 1 image for "Vend" and 1 or more for "Achète". images are separeted by ':$.' ex: img1.png:$.img2.png:$.img3.png

# all model names are differents
model_names = ['Air Jordan 4 Retro OG GS \'Bred\' 2019.png',
'Air Jordan 4 Retro OG \'Bred\' 2019.png',
'Air Jordan 11 Retro \'Space Jam\' 2016.png',
'Air Jordan 11 Retro \'Win Like \'96.png',
'Air Jordan 1 Retro High OG \'Shadow\' 2018.png',
'Air Jordan 1 Retro High OG \'Origin Story\'.png',
'Jordan 1 Retro High Shattered Backboard 3.0.webp',
'Air Jordan 6 Retro \'Infrared\' 2019.png',
'Air Jordan 1 Retro High OG \'Rookie of the Year\'.png',
'Trophy Room x Air Jordan 5 Retro \'Ice Blue\'.png',
'Travis Scott x Air Jordan 1 Retro High OG \'Mocha\'.png',
'Air Jordan 12 Retro \'Gym Red\'.png',
'Air Jordan 4 Retro \'Laser\'.png',
'Wmns Air Jordan 1 Retro High OG \'Twist\'.png',
'Patta x Air Jordan 7 Retro OG SP \'Shimmer\'.png',
'Air Jordan 1 Mid \'Multicolor Swoosh Black\'.png',
'OFF-WHITE x Air Jordan 1 Retro High OG \'UNC\'.png',
'Air Jordan 1 Retro High OG \'Bred Toe\'.png',
'Travis Scott x Air Jordan 4 Retro \'Cactus Jack\'.png',
'Air Jordan 1 Retro High SB \'Lakers\'.png',
'Air Jordan 1 Retro High OG \'Crimson Tint\'.png',
'Air Jordan 11 Retro \'Legend Blue\' 2014.png',
'Wmns Air Jordan 12 Retro \'Reptile\'.png',
'Wmns Air Jordan 11 Retro Low \'Pink Snakeskin\'.png']


import random

# date format :2023-05-31 03:17:41

def getrandomdate():
    year = random.randint(2020, 2023)
    month = random.randint(1, 12)
    day = random.randint(1, 28)
    hour = random.randint(0, 23)
    minute = random.randint(0, 59)
    second = random.randint(0, 59)
    return f'{year}-{month}-{day} {hour}:{minute}:{second}'
def generate_post():
    post_id = random.randint(1000000, 9999999)
    date_post = getrandomdate()
    model_name = random.choice(model_names)
    post_type = random.choice(post_types)
    price = random.randint(30, 450)
    size = random.randint(33, 47)
    used = used_list[random.randint(0, 1)]
    model_id = random.randint(0, 999999)
    photo = model_name
    user_id = random.choice(user_ids)

    return (post_id,date_post, model_name, post_type, price, size, used, model_id, photo, user_id)

def generate_posts():
    posts = []
    for i in range(5000):
        posts.append(generate_post())
    return posts

def generate_sql_insert(posts):
    sql = """INSERT INTO `post` (`post_id`,`date_post`, `model_name`, `post_type`, `price`, `size`, `used`, `model_id`, `photo`, `user_id`) VALUES """
    for post in posts:
        sql += str(post) + ','
    sql = sql[:-1] + ';'
    return sql

print(generate_sql_insert(generate_posts()))