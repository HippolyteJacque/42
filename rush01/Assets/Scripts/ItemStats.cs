using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class ItemStats : MonoBehaviour {

	public int type; // 0 = common, 1=rare, 2=epic, 3=legendaire
	public float damage;
	public float attackSpeed;

	public void InitStat()
	{
		int i = Random.Range(0, 100);
		if (i < 50)
			type = 0;
		else if (i >= 50 && i < 75)
			type = 1;
		else if (i >= 75 && i < 90)
			type = 2;
		else if (i >= 90)
			type = 3;

		float dmgBase = MayaMove.instance.Level * 5 * (type+1);
		damage = Random.Range(dmgBase * 0.5f, damage * 1.5f);
		damage = Mathf.RoundToInt(damage);
		attackSpeed = Random.Range(0.75f, 1.2f);

		SpriteRenderer tmp = GetComponent<SpriteRenderer>();
		if (tmp != null)
			GetComponent<SpriteRenderer>().sprite = SpritePool.instance.GetRandomSprite();
	}
	void Start () {

	}
	
	// Update is called once per frame
	void Update () {
		
	}
}
