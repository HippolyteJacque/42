using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class SpritePool : MonoBehaviour {

	public GameObject lifeBall;
	public GameObject itemInGame;
	public GameObject itemInUi;
	public static SpritePool instance;

	void Awake()
	{
		if (instance != null)
		{
			Destroy(instance.gameObject);
		}
		instance = this;
			
	}

	public Object[] textures;
	void Start () {
		textures = Resources.LoadAll("Sprites", typeof(Sprite));
	}
	
	public Sprite GetRandomSprite()
	{
		return ((Sprite)textures[Random.Range(0, textures.Length)]);
	}
}
